<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Get chat history from session.
     */
    public function history()
    {
        $lang = session('chat_lang');
        $history = session('chat_history', []);
        
        if (empty($lang)) {
            return response()->json([
                'history' => [],
                'needs_language_selection' => true
            ]);
        }

        if (empty($history)) {
            $history = [$this->getWelcomeMessage($lang)];
            session(['chat_history' => $history]);
        }

        return response()->json([
            'history' => $history,
            'needs_language_selection' => false,
            'lang' => $lang
        ]);
    }

    /**
     * Initialize chat in a specific language.
     */
    public function start(Request $request)
    {
        $request->validate([
            'lang' => 'required|string|in:en,fr,ar',
        ]);

        $lang = $request->input('lang');
        session(['chat_lang' => $lang]);
        
        $welcome = $this->getWelcomeMessage($lang);
        session(['chat_history' => [$welcome]]);

        return response()->json([
            'status' => 'success',
            'lang' => $lang,
            'history' => [$welcome]
        ]);
    }

    /**
     * Handle incoming chat message.
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $lang = session('chat_lang', 'fr'); // default fallback
        
        // Append user message to history
        $history = session('chat_history', []);
        $history[] = [
            'sender' => 'user',
            'text' => e($userMessage),
            'timestamp' => now()->format('H:i')
        ];

        // Generate response (dynamic LLM or local fallback)
        $responseHTML = $this->generateResponse($userMessage, $lang, $history);

        $history[] = [
            'sender' => 'ai',
            'text' => $responseHTML,
            'timestamp' => now()->format('H:i')
        ];

        session(['chat_history' => $history]);

        return response()->json([
            'message' => $responseHTML,
            'history' => $history
        ]);
    }

    /**
     * Clear chat history and go back to language selection.
     */
    public function clear()
    {
        session()->forget('chat_lang');
        session()->forget('chat_history');

        return response()->json([
            'status' => 'success',
            'message' => 'Chat reset.',
            'history' => [],
            'needs_language_selection' => true
        ]);
    }

    /**
     * Get default welcome message based on language.
     */
    private function getWelcomeMessage($lang)
    {
        switch ($lang) {
            case 'en':
                return [
                    'sender' => 'ai',
                    'text' => "Hello! I am the virtual assistant for **APEX CAR**.<br>How can I help you today?<br><br>You can ask me about:<br>• 🚘 Cars in our stock (e.g., *\"Do you have BMW?\"*)<br>• 💰 Your budget (e.g., *\"Cars under 250,000\"*)<br>• 🛠️ Condition (e.g., *\"New cars\"* or *\"Used cars\"*)<br>• 📞 Our contact details or the reservation process",
                    'timestamp' => now()->format('H:i')
                ];
            case 'ar':
                return [
                    'sender' => 'ai',
                    'text' => "مرحباً! أنا المساعد الافتراضي لـ **APEX CAR**.<br>كيف يمكنني مساعدتك اليوم؟<br><br>يمكنك سؤالي عن:<br>• 🚘 السيارات المتاحة في مخزوننا (مثال: *\"هل لديكم بي ام دبليو؟\"*)<br>• 💰 ميزانيتك (مثال: *\"سيارات بأقل من 250,000\"*)<br>• 🛠️ الحالة (مثال: *\"سيارات جديدة\"* أو *\"مستعملة\"*)<br>• 📞 معلومات الاتصال أو طريقة الحجز والشراء",
                    'timestamp' => now()->format('H:i')
                ];
            case 'fr':
            default:
                return [
                    'sender' => 'ai',
                    'text' => "Bonjour ! Je suis l'assistant virtuel d'**APEX CAR**.<br>Comment puis-je vous aider aujourd'hui ?<br><br>Vous pouvez me poser des questions sur :<br>• 🚘 Les voitures de notre stock (ex: *\"Avez-vous des BMW ?\"*)<br>• 💰 Votre budget (ex: *\"Voiture moins de 250 000\"*)<br>• 🛠️ Les conditions (ex: *\"Voitures neuves\"* ou *\"d'occasion\"*)<br>• 📞 Nos coordonnées ou le processus de réservation",
                    'timestamp' => now()->format('H:i')
                ];
        }
    }

    /**
     * Main response generator (decides whether to use Gemini LLM or Local search engine).
     */
    private function generateResponse($message, $lang, $history)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (!empty($apiKey)) {
            $llmResponse = $this->callGeminiAPI($message, $lang, $history, $apiKey);
            if ($llmResponse !== null) {
                return $llmResponse;
            }
        }
        
        // Fallback to local keyword-based search engine
        return $this->generateLocalResponse($message, $lang);
    }

    /**
     * Call Google Gemini API for smart conversational responses.
     */
    private function callGeminiAPI($message, $lang, $history, $apiKey)
    {
        // 1. Gather all available cars as real-time context
        $cars = Car::with(['carModel.brand'])->where('status', 'available')->get();
        $inventoryLines = [];
        
        foreach ($cars as $car) {
            $image = ($car->images && count($car->images) > 0) 
                ? asset('storage/' . $car->images[0]) 
                : asset('images/car-placeholder.png');
            
            $url = route('cars.show', $car);
            
            $inventoryLines[] = sprintf(
                "- Brand: %s | Model: %s | Year: %d | Price: %s $ | Mileage: %s km | Fuel: %s | Transmission: %s | Condition: %s | Link: %s | Image: %s",
                $car->carModel->brand->name,
                $car->carModel->name,
                $car->year,
                number_format($car->price),
                number_format($car->mileage),
                $car->fuel_type,
                $car->transmission,
                $car->condition,
                $url,
                $image
            );
        }
        
        $inventoryContext = implode("\n", $inventoryLines);
        
        // 2. Build system instructions
        $detailsLabel = 'Détails';
        if ($lang === 'en') { $detailsLabel = 'Details'; }
        elseif ($lang === 'ar') { $detailsLabel = 'التفاصيل'; }

        $systemInstruction = <<<PROMPT
You are "APEX AI", the premium virtual assistant for "APEX CAR" — a luxury car showroom located in Oujda, Morocco.

## YOUR PERSONALITY
- You are warm, intelligent, witty, and genuinely helpful — like a knowledgeable friend who happens to be a car expert.
- You have a conversational, natural tone. You are NOT robotic. You use short sentences, ask follow-up questions, and show enthusiasm.
- You adapt your style to the user: casual if they're casual, formal if they're formal.
- You use emojis sparingly but effectively (1-2 per message max, not every sentence).
- You NEVER say "I am an AI" or "As an AI assistant" — you simply act as the showroom's smart concierge.
- If someone asks something outside of cars/showroom, politely redirect while being charming about it.

## LANGUAGE
- Respond ONLY in the language code: "{$lang}" (ar = Arabic, fr = French, en = English).
- If Arabic ("ar"), write natural, modern Arabic. The UI handles RTL rendering.
- Match the user's formality level and energy.

## YOUR KNOWLEDGE
### Showroom Info
- Name: APEX CAR
- Location: Oujda, Morocco
- Hours: Mon–Sat, 9 AM – 7 PM. Online support: 24/7.
- Phone: +212 600 000 000
- Email: contact@apexcar.ma

### How Booking Works
1. Browse the showroom at /cars
2. Click "Voir Détails" (or Details) on a car
3. Fill the reservation form (login required)
4. Team calls within 24 hours to finalize

### Comparison Feature
- Click the scale icon ⚖️ on any car card
- Go to /compare to see specs side-by-side

## REAL-TIME INVENTORY
Below is our LIVE stock. When recommending cars, show MAX 3 at a time. For EACH car, output this EXACT HTML (do NOT wrap in code blocks, do NOT use markdown links):

<div class='flex gap-3 bg-zinc-950/60 p-3 rounded-xl border border-white/5 hover:border-amber-600/30 transition-all mb-3'>
<div class='w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-zinc-900'>
<img src='[IMAGE_URL]' class='w-full h-full object-cover no-invert' alt='[MODEL]'>
</div>
<div class='flex-1 min-w-0 flex flex-col justify-between'>
<div>
<div class='text-[10px] text-amber-500 font-extrabold uppercase tracking-widest leading-none mb-1'>[BRAND]</div>
<h4 class='text-xs font-black text-white truncate leading-tight'>[MODEL]</h4>
<div class='text-[10px] text-gray-500 mt-1 flex gap-2'>
<span>[YEAR]</span><span>•</span><span>[MILEAGE] km</span>
</div>
</div>
<div class='flex justify-between items-center mt-2'>
<span class='text-xs font-black text-white'>[PRICE]</span>
<a href='[LINK]' class='text-[10px] bg-amber-600 hover:bg-amber-500 text-white font-extrabold px-3 py-1.5 rounded-lg transition-all active:scale-95 uppercase tracking-wider' target='_parent'>{$detailsLabel}</a>
</div>
</div>
</div>

Replace: [IMAGE_URL], [BRAND], [MODEL], [YEAR], [MILEAGE], [PRICE], [LINK] with actual data.

### CURRENT STOCK:
{$inventoryContext}

## RESPONSE RULES
1. Keep responses concise but helpful (2-4 sentences + car cards if relevant). Don't write essays.
2. If the user's question matches inventory, show matching cars with the HTML card format above.
3. If we don't have what they want, say so honestly and suggest alternatives from stock.
4. Ask clarifying questions when helpful: "What's your budget range?" or "Do you prefer automatic or manual?"
5. Be proactive: if someone asks about a BMW, also mention similar alternatives.
6. Use **bold** for emphasis and <br> for line breaks. Never use markdown code blocks.
7. For contact info, use clickable HTML: <a href='tel:+212600000000'>+212 600 000 000</a>
8. When greeting, be brief and warm — don't repeat the full menu of capabilities.
9. Remember conversation context from previous messages.
10. If someone says "thank you" or similar, respond warmly and naturally.
PROMPT;

        // 3. Prepare message payload (including last 6 messages of history for better memory)
        $contents = [];
        $recentHistory = array_slice($history, -7, 6);
        
        foreach ($recentHistory as $msg) {
            $role = $msg['sender'] === 'user' ? 'user' : 'model';
            // strip tags to prevent sending raw HTML styling back to the model context
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => strip_tags($msg['text'])]
                ]
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $message]
            ]
        ];

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'contents' => $contents,
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 2000,
                    'topP' => 0.9
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $textResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if ($textResponse) {
                    // Remove any accidental markdown html blocks (e.g. ```html ... ```) the model might generate
                    $textResponse = preg_replace('/```html\s*(.*?)\s*```/is', '$1', $textResponse);
                    $textResponse = preg_replace('/```\s*(.*?)\s*```/is', '$1', $textResponse);
                    return trim($textResponse);
                }
            } else {
                \Log::warning('Gemini API request failed with status: ' . $response->status() . ' - Body: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Gemini API Chat error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Fallback Response Generator (Local keyword search engine).
     */
    private function generateLocalResponse($message, $lang)
    {
        $cleanMessage = Str::lower(trim($message));
        
        // Normalize spaces and common currency/number characters
        $normalizedMessage = preg_replace('/(\d+)\s+(?=\d)/', '$1', $cleanMessage);
        $normalizedMessage = str_replace(['.', ',', 'dh', 'usd', '$'], '', $normalizedMessage);

        // Detect language or fallback to session
        $isArabic = preg_match('/[\x{0600}-\x{06FF}]/u', $cleanMessage);
        $detectedLang = $isArabic ? 'ar' : $lang;

        // 1. GREETINGS & INTROS
        $isGreeting = preg_match('/\b(hello|hi|hey|yo|greetings|bonjour|salut|bonsoir|coucou|hola|مرحبا|اهلا|أهلا|السلام عليكم|صباح الخير|مساء الخير)\b/iu', $cleanMessage);
        if ($isGreeting) {
            switch ($detectedLang) {
                case 'en':
                    return "Hello! I am **APEX AI**, your luxury car concierge. 😊 How can I assist you today?<br><br>" .
                           "Feel free to ask me about:<br>" .
                           "• 🚘 *\"What cars do you have?\"*<br>" .
                           "• 🛠️ *\"Do you have used or new models?\"*<br>" .
                           "• 💰 *\"Show me cars under 300,000\"*<br>" .
                           "• 📞 *\"How can I contact you?\"*";
                case 'ar':
                    return "مرحباً بك! أنا **APEX AI**، المساعد الذكي لمعرض APEX CAR. 🚗 كيف يمكنني مساعدتك اليوم؟<br><br>" .
                           "يمكنك الاستفسار عن:<br>" .
                           "• 🚘 *\"ما هي السيارات المتوفرة؟\"*<br>" .
                           "• 🛠️ *\"سيارات جديدة أو مستعملة؟\"*<br>" .
                           "• 💰 *\"سيارات بأقل من 300,000\"*<br>" .
                           "• 📞 *\"كيف يمكنني التواصل معكم؟\"*";
                case 'fr':
                default:
                    return "Bonjour ! Je suis **APEX AI**, votre conseiller automobile virtuel. 😊 Comment puis-je vous aider aujourd'hui ?<br><br>" .
                           "Vous pouvez me poser des questions sur :<br>" .
                           "• 🚘 *\"Quelles voitures avez-vous en stock ?\"*<br>" .
                           "• 🛠️ *\"Proposez-vous des voitures neuves ou d'occasion ?\"*<br>" .
                           "• 💰 *\"Montre-moi des voitures à moins de 300 000\"*<br>" .
                           "• 📞 *\"Comment réserver un véhicule ou vous contacter ?\"*";
            }
        }

        // 2. CONTACT / PHONE / LOCATION
        $isContact = preg_match('/\b(contact|address|phone|email|mail|number|location|open|hour|hours|map|adresse|telephone|horaire|ouvert|oujda|appel|contacter|اتصال|تواصل|عنوان|هاتف|رقم|بريد|ايميل|وجدة)\b/iu', $cleanMessage);
        if ($isContact) {
            switch ($detectedLang) {
                case 'en':
                    return "Here is how you can connect with the **APEX CAR** team directly:<br><br>" .
                           "📞 **Phone / WhatsApp**: <a href='tel:+212600000000' class='text-amber-500 font-bold hover:underline'>+212 600 000 000</a><br>" .
                           "✉️ **Email**: <a href='mailto:contact@apexcar.ma' class='text-amber-500 font-bold hover:underline'>contact@apexcar.ma</a><br>" .
                           "📍 **Address**: Oujda, Morocco<br>" .
                           "⏰ **Showroom Hours**: Monday to Saturday, 9:00 AM – 7:00 PM. Our online support is ready to guide you 24/7!";
                case 'ar':
                    return "يسعدنا تواصلكم معنا! إليكم قنوات الاتصال المباشرة لمعرض **APEX CAR**:<br><br>" .
                           "📞 **الهاتف / واتساب**: <a href='tel:+212600000000' class='text-amber-500 font-bold hover:underline'>+212 600 000 000</a><br>" .
                           "✉️ **البريد الإلكتروني**: <a href='mailto:contact@apexcar.ma' class='text-amber-500 font-bold hover:underline'>contact@apexcar.ma</a><br>" .
                           "📍 **العنوان**: وجدة، المغرب<br>" .
                           "⏰ **أوقات العمل**: من الاثنين إلى السبت، من 9:00 صباحاً حتى 7:00 مساءً. الدعم عبر الإنترنت متوفر على مدار الساعة!";
                case 'fr':
                default:
                    return "Vous pouvez entrer en contact direct avec l'équipe d'**APEX CAR** via les canaux suivants :<br><br>" .
                           "📞 **Téléphone / WhatsApp** : <a href='tel:+212600000000' class='text-amber-500 font-bold hover:underline'>+212 600 000 000</a><br>" .
                           "✉️ **Email** : <a href='mailto:contact@apexcar.ma' class='text-amber-500 font-bold hover:underline'>contact@apexcar.ma</a><br>" .
                           "📍 **Adresse** : Oujda, Maroc<br>" .
                           "⏰ **Horaires** : Du lundi au samedi, de 9:00 à 19:00. Le support en ligne est actif 24h/24 et 7j/7 !";
            }
        }

        // 3. BOOKING / PROCESS
        $isBooking = preg_match('/\b(reserve|reservation|book|buy|order|purchase|how to|process|reserver|acheter|commande|achat|comment|حجز|شراء|طلب|كيف)\b/iu', $cleanMessage);
        if ($isBooking && !preg_match('/\b(car|cars|voiture|voitures|سيارة|سيارات)\b/iu', $cleanMessage)) {
            switch ($detectedLang) {
                case 'en':
                    return "Reserving your dream car on **APEX CAR** is fully streamlined:<br><br>" .
                           "1. Head to our online <a href='/cars' class='text-amber-500 font-bold hover:underline'>Showroom</a> and pick a model.<br>" .
                           "2. Click **View Details** to inspect specifications, dynamic photos, and options.<br>" .
                           "3. Log in to your account and complete the quick reservation form.<br>" .
                           "4. Our showroom manager will get in touch with you within 24 hours to organize your showroom visit and delivery!";
                case 'ar':
                    return "حجز سيارتك المفضلة عبر معرض **APEX CAR** سهل للغاية:<br><br>" .
                           "1. تصفح <a href='/cars' class='text-amber-500 font-bold hover:underline'>معرض السيارات</a> واختر الطراز المناسب.<br>" .
                           "2. اضغط على **عرض التفاصيل** للاطلاع على كافة المواصفات والصور والخيارات.<br>" .
                           "3. قم بتسجيل الدخول واملأ نموذج الحجز البسيط.<br>" .
                           "4. سيتصل بك فريقنا في غضون 24 ساعة لترتيب موعد الزيارة والتسليم!";
                case 'fr':
                default:
                    return "Réserver votre véhicule sur **APEX CAR** est simple et rapide :<br><br>" .
                           "1. Rendez-vous sur notre <a href='/cars' class='text-red-500 font-bold hover:underline'>Showroom en ligne</a> et choisissez un modèle.<br>" .
                           "2. Cliquez sur **Voir Détails** pour afficher les caractéristiques complètes et les photos.<br>" .
                           "3. Connectez-vous à votre compte et remplissez le formulaire de réservation.<br>" .
                           "4. Notre équipe commerciale vous recontactera sous 24 heures pour planifier votre visite et finaliser l'acquisition !";
            }
        }

        // 4. BUDGET SEARCH
        $budgetLimit = 0;
        $hasBudgetTrigger = false;
        
        // Match numbers like "250000", "250,000", "250 000", or shorthand "250k" / "250 k"
        $modifiedQuery = preg_replace('/(\d+)\s*k\b/i', '${1}000', $normalizedMessage);
        
        if (preg_match('/\b(under|less than|max|maximum|budget|price|moins de|sous|inferieur|اقل من|أقل من|تحت|سعر|اقصى|أقصى)\b.*?\b(\d{4,9})\b/iu', $modifiedQuery, $matches) ||
            preg_match('/\b(\d{4,9})\b.*?\b(under|less than|max|maximum|budget|price|moins de|sous|inferieur|اقل من|أقل من|تحت|سعر|اقصى|أقصى)\b/iu', $modifiedQuery, $matches)) {
            $hasBudgetTrigger = true;
            $budgetLimit = (int)end($matches);
        }

        if ($hasBudgetTrigger && $budgetLimit > 0) {
            $cars = Car::with(['carModel.brand'])->where('price', '<=', $budgetLimit)->where('status', 'available')->latest()->take(3)->get();
            if ($cars->count() > 0) {
                switch ($detectedLang) {
                    case 'en':
                        return "Perfect! I found these superb models in our stock under your budget of **" . number_format($budgetLimit) . " $**:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'ar':
                        return "رائع! عثرت على هذه الموديلات الرائعة في مخزوننا بميزانية أقل من **" . number_format($budgetLimit) . " $**:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'fr':
                    default:
                        return "Excellente idée ! J'ai trouvé ces modèles disponibles dans notre showroom sous votre budget de **" . number_format($budgetLimit) . " $** :<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                }
            } else {
                switch ($detectedLang) {
                    case 'en':
                        return "I couldn't find any models under **" . number_format($budgetLimit) . " $** in stock right now. You can check our <a href='/cars' class='text-amber-500 font-bold hover:underline'>complete catalog</a> to explore other luxury options!";
                    case 'ar':
                        return "لم أجد سيارات بأقل من **" . number_format($budgetLimit) . " $** حالياً. يمكنك إلقاء نظرة على <a href='/cars' class='text-amber-500 font-bold hover:underline'>كتالوج السيارات الكامل</a> لتصفح الخيارات الفاخرة المتاحة!";
                    case 'fr':
                    default:
                        return "Je n'ai pas trouvé de modèles en dessous de **" . number_format($budgetLimit) . " $** pour le moment. Découvrez notre <a href='/cars' class='text-amber-500 font-bold hover:underline'>catalogue complet</a> pour explorer d'autres opportunités de prestige !";
                }
            }
        }

        // 5. BRAND SEARCH (Mercedes, BMW, Toyota, Porsche, etc.)
        $brands = Brand::all();
        $matchedBrand = null;
        
        foreach ($brands as $brand) {
            $brandNameLower = Str::lower($brand->name);
            if (Str::contains($cleanMessage, $brandNameLower)) {
                $matchedBrand = $brand;
                break;
            }
        }
        
        // Multi-language brand mappings
        if (!$matchedBrand) {
            $brandAliases = [
                'BMW' => ['bmw', 'بي ام', 'بي أم', 'بي إم'],
                'Mercedes-Benz' => ['mercedes', 'mercedes-benz', 'مرسيدس', 'مرسيديس'],
                'Audi' => ['audi', 'أودي', 'اودي'],
                'Porsche' => ['porsche', 'بورش', 'بورشه'],
                'Toyota' => ['toyota', 'تويوتا'],
                'Ferrari' => ['ferrari', 'فيراري'],
                'Tesla' => ['tesla', 'تسلا', 'تيسلا'],
                'Volkswagen' => ['volkswagen', 'golf', 'gti', 'فولكس', 'غولف', 'جولف']
            ];
            
            foreach ($brandAliases as $realName => $aliases) {
                foreach ($aliases as $alias) {
                    if (Str::contains($cleanMessage, $alias)) {
                        $matchedBrand = Brand::where('name', 'like', "%{$realName}%")->first();
                        if ($matchedBrand) break 2;
                    }
                }
            }
        }

        if ($matchedBrand) {
            $cars = Car::with(['carModel.brand'])->whereHas('carModel', function($q) use ($matchedBrand) { $q->where('brand_id', $matchedBrand->id); })->where('status', 'available')->latest()->take(3)->get();
            if ($cars->count() > 0) {
                switch ($detectedLang) {
                    case 'en':
                        return "Yes, absolutely! We have the following **{$matchedBrand->name}** models ready for you:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'ar':
                        return "بالتأكيد! متوفر لدينا حالياً موديلات ماركة **{$matchedBrand->name}** التالية في صالة العرض:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'fr':
                    default:
                        return "Absolument ! Nous disposons des modèles **{$matchedBrand->name}** suivants dans notre stock actuel :<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                }
            } else {
                switch ($detectedLang) {
                    case 'en':
                        return "We don't have any **{$matchedBrand->name}** models in our inventory today. Take a look at our <a href='/cars' class='text-amber-500 font-bold hover:underline'>full showroom</a> to see similar premium cars!";
                    case 'ar':
                        return "لا يتوفر لدينا طرازات من ماركة **{$matchedBrand->name}** اليوم. تصفح <a href='/cars' class='text-amber-500 font-bold hover:underline'>المعرض العام</a> لمشاهدة سيارات فاخرة بديلة!";
                    case 'fr':
                    default:
                        return "Nous n'avons pas de modèles de la marque **{$matchedBrand->name}** disponibles aujourd'hui. N'hésitez pas à parcourir le <a href='/cars' class='text-amber-500 font-bold hover:underline'>showroom complet</a> pour découvrir d'autres alternatives !";
                }
            }
        }

        // 6. CONDITION SEARCH (New vs Used)
        $wantsNew = preg_match('/\b(new|neuf|neuve|neuves|nouvelles?|جديد|جديدة|جديده)\b/iu', $cleanMessage);
        $wantsUsed = preg_match('/\b(used|second hand|preowned|occasion|occasions|seconde main|usage|مستعمل|مستعملة|مستعمله)\b/iu', $cleanMessage);

        if ($wantsNew) {
            $cars = Car::with(['carModel.brand'])->where('condition', 'neuf')->where('status', 'available')->latest()->take(3)->get();
            if ($cars->count() > 0) {
                switch ($detectedLang) {
                    case 'en':
                        return "Here are our latest **new** arrivals in the showroom:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'ar':
                        return "إليك أحدث السيارات **الجديدة** كلياً في صالة العرض:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'fr':
                    default:
                        return "Voici nos plus récents véhicules **neufs** disponibles en showroom :<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                }
            }
        }

        if ($wantsUsed) {
            $cars = Car::with(['carModel.brand'])->where('condition', 'occasion')->where('status', 'available')->latest()->take(3)->get();
            if ($cars->count() > 0) {
                switch ($detectedLang) {
                    case 'en':
                        return "Here are our certified, multi-point inspected **used** vehicles:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'ar':
                        return "إليك تشكيلة من سياراتنا **المستعملة** المضمونة والمفحوصة بالكامل:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'fr':
                    default:
                        return "Voici notre sélection de véhicules **d'occasion** inspectés et garantis :<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                }
            }
        }

        // 7. GENERIC CARS STOCK REQUEST
        $wantsCarsList = preg_match('/\b(car|cars|vehicle|vehicles|stock|inventory|list|showroom|voiture|voitures|vehicule|vehicules|سيارة|سيارات|المخزون|المعرض)\b/iu', $cleanMessage);
        if ($wantsCarsList) {
            $cars = Car::with(['carModel.brand'])->where('status', 'available')->latest()->take(3)->get();
            if ($cars->count() > 0) {
                switch ($detectedLang) {
                    case 'en':
                        return "Here is a snapshot of some amazing cars currently in our showroom:<br><br>" . $this->renderCarsHtml($cars, $detectedLang) . "<br>Browse the full list at our online <a href='/cars' class='text-amber-500 font-bold hover:underline'>Showroom</a>!";
                    case 'ar':
                        return "إليك لمحة عن السيارات المتميزة المتاحة لدينا حالياً:<br><br>" . $this->renderCarsHtml($cars, $detectedLang) . "<br>تصفح المعرض الكامل في <a href='/cars' class='text-red-500 font-bold hover:underline'>صفحة المعرض</a>!";
                    case 'fr':
                    default:
                        return "Voici un aperçu de quelques superbes modèles disponibles chez nous :<br><br>" . $this->renderCarsHtml($cars, $detectedLang) . "<br>Consultez l'intégralité du stock dans notre <a href='/cars' class='text-red-500 font-bold hover:underline'>Showroom</a> !";
                }
            }
        }

        // 8. GENERAL TEXT MATCHING / SEARCH DB
        if (strlen($cleanMessage) > 3) {
            $cars = Car::with(['carModel.brand'])
                ->where(function($query) use ($cleanMessage) {
                    $query->where('description', 'like', "%{$cleanMessage}%")
                          ->orWhere('fuel_type', 'like', "%{$cleanMessage}%")
                          ->orWhere('transmission', 'like', "%{$cleanMessage}%")
                          ->orWhere('color', 'like', "%{$cleanMessage}%")
                          ->orWhereHas('carModel', function($q) use ($cleanMessage) {
                              $q->where('name', 'like', "%{$cleanMessage}%")
                                ->orWhere('type', 'like', "%{$cleanMessage}%");
                          });
                })
                ->where('status', 'available')
                ->latest()
                ->take(3)
                ->get();

            if ($cars->count() > 0) {
                switch ($detectedLang) {
                    case 'en': return "I found these vehicles matching your request:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'ar': return "عثرت على هذه السيارات المتطابقة مع طلبك:<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                    case 'fr':
                    default: return "J'ai trouvé ces modèles correspondant à votre demande :<br><br>" . $this->renderCarsHtml($cars, $detectedLang);
                }
            }
        }

        // 9. CHITCHAT fallback (make it feel like a real conversational chatbot instead of failing)
        $chitChatResponses = [
            'en' => [
                'thanks' => "You're very welcome! If you need anything else, just ask. Have an awesome day! 😊",
                'who' => "I am **APEX AI**, the intelligent assistant built for APEX CAR showroom. I can show you cars, filter by budget, or help you book your next ride!",
                'default' => "I want to make sure I answer correctly! I can help you search our premium inventory. Try asking me:\n" .
                             "• 🚘 *\"Do you have Mercedes models?\"*\n" .
                             "• 🛠️ *\"Show me used cars\"*\n" .
                             "• 💰 *\"What cars do you have under 400,000?\"*\n" .
                             "• 📞 *\"What is your phone number?\"*"
            ],
            'ar' => [
                'thanks' => "على الرحب والسعة! أنا هنا دائماً لمساعدتك. أتمنى لك يوماً سعيداً! 😊",
                'who' => "أنا **APEX AI**، المساعد الافتراضي لمعرض APEX CAR. يمكنني مساعدتك في العثور على سيارتك، فلترة الأسعار، وتوضيح كيفية الحجز والتواصل!",
                'default' => "أود الإجابة بدقة! يمكنني مساعدتك في تصفح مخزوننا الفاخر. جرب أن تسألني:\n" .
                             "• 🚘 *\"هل لديكم مرسيدس؟\"*\n" .
                             "• 🛠️ *\"عرض السيارات المستعملة\"*\n" .
                             "• 💰 *\"ما هي السيارات بأقل من 400,000؟\"*\n" .
                             "• 📞 *\"ما هو رقم الهاتف؟\"*"
            ],
            'fr' => [
                'thanks' => "Je vous en prie ! C'est un plaisir de vous aider. N'hésitez pas si vous avez d'autres questions. 😊",
                'who' => "Je suis **APEX AI**, le conseiller virtuel d'APEX CAR. Je suis conçu pour vous présenter notre catalogue, filtrer par budget ou vous expliquer comment réserver !",
                'default' => "Je veux m'assurer de bien vous répondre ! Je peux vous aider à explorer nos voitures. Essayez de me demander :\n" .
                             "• 🚘 *\"Avez-vous des modèles Mercedes ?\"*\n" .
                             "• 🛠️ *\"Montre-moi les voitures d'occasion\"*\n" .
                             "• 💰 *\"Quelles voitures avez-vous à moins de 400 000 ?\"*\n" .
                             "• 📞 *\"Quel est votre numéro de téléphone ?\"*"
            ]
        ];

        $topic = 'default';
        if (preg_match('/\b(thank|thanks|merci|shukran|chokran|شكرا|شكرًا)\b/iu', $cleanMessage)) {
            $topic = 'thanks';
        } elseif (preg_match('/\b(who are you|your name|ton nom|qui es tu|qui es-tu|من انت|من أنت|اسمك)\b/iu', $cleanMessage)) {
            $topic = 'who';
        }

        $reply = $chitChatResponses[$detectedLang][$topic] ?? $chitChatResponses['fr'][$topic];
        
        // Append admin configuration warning for local debug mode
        if (config('app.debug') && config('app.env') === 'local') {
            $reply .= "<br><br><span class='text-[10px] text-yellow-500/70 block border-t border-white/5 pt-3'>💡 **Admin Note:** The AI chatbot is running in offline keyword fallback mode. To enable full ChatGPT-like responses, please get a free key from [Google AI Studio](https://aistudio.google.com/) and update your `GEMINI_API_KEY` in `.env`.</span>";
        }

        return nl2br($reply);
    }

    /**
     * Helper to render car cards inside chat.
     */
    private function renderCarsHtml($cars, $lang)
    {
        $html = '<div class="space-y-3">';
        
        $detailsLabel = 'Détails';
        if ($lang === 'en') { $detailsLabel = 'Details'; }
        elseif ($lang === 'ar') { $detailsLabel = 'التفاصيل'; }
        
        foreach ($cars as $car) {
            $image = ($car->images && count($car->images) > 0) 
                ? asset('storage/' . $car->images[0]) 
                : asset('images/car-placeholder.png');
            
            $url = route('cars.show', $car);
            $priceFormatted = number_format($car->price) . ' $';
            
            $fuel = $car->fuel_type;
            $trans = $car->transmission;
            if ($lang === 'en') {
                if (strtolower($fuel) === 'essence') { $fuel = 'Petrol'; }
                if (strtolower($trans) === 'automatique') { $trans = 'Automatic'; }
                if (strtolower($trans) === 'manuelle') { $trans = 'Manual'; }
            } elseif ($lang === 'ar') {
                if (strtolower($fuel) === 'essence') { $fuel = 'بنزين'; }
                if (strtolower($fuel) === 'diesel') { $fuel = 'ديزل'; }
                if (strtolower($trans) === 'automatique') { $trans = 'أوتوماتيك'; }
                if (strtolower($trans) === 'manuelle') { $trans = 'يدوي'; }
            }
            
            $mileageFormatted = number_format($car->mileage) . ' km';
            
            $html .= "
            <div class='flex gap-3 bg-zinc-950/60 p-3 rounded-xl border border-white/5 hover:border-amber-600/30 transition-all'>
                <div class='w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-zinc-900'>
                    <img src='{$image}' class='w-full h-full object-cover no-invert' alt='{$car->carModel->name}'>
                </div>
                <div class='flex-1 min-w-0 flex flex-col justify-between'>
                    <div>
                        <div class='text-[10px] text-amber-500 font-extrabold uppercase tracking-widest leading-none mb-1'>{$car->carModel->brand->name}</div>
                        <h4 class='text-xs font-black text-white truncate leading-tight'>{$car->carModel->name}</h4>
                        <div class='text-[10px] text-gray-500 mt-1 flex gap-2'>
                            <span>{$car->year}</span>
                            <span>•</span>
                            <span>{$mileageFormatted}</span>
                        </div>
                    </div>
                    <div class='flex justify-between items-center mt-2'>
                        <span class='text-xs font-black text-white'>{$priceFormatted}</span>
                        <a href='{$url}' class='text-[10px] bg-amber-600 hover:bg-amber-500 text-white font-extrabold px-3 py-1.5 rounded-lg transition-all active:scale-95 uppercase tracking-wider' target='_parent'>{$detailsLabel}</a>
                    </div>
                </div>
            </div>";
        }
        
        $html .= '</div>';
        return $html;
    }
}
