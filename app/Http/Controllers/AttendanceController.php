<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;

class AttendanceController extends Controller
{
    public function scan()
    {
        return view('attendance.scan');
    }

    public function record(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'selfie' => 'nullable|string',
        ]);

        $worker = Worker::where('qr_token', $request->token)
            ->where('status', 'active')
            ->first();

        if (!$worker) {
            return response()->json(['success' => false, 'message' => 'Invalid or inactive QR code.'], 404);
        }

        // Handle GPS verification if branch has radius
        if ($worker->branch && $worker->branch->latitude && $worker->branch->longitude && $request->latitude && $request->longitude) {
            $distance = $this->calculateDistance(
                $worker->branch->latitude, $worker->branch->longitude,
                $request->latitude, $request->longitude
            );
            if ($distance > $worker->branch->radius_meters) {
                return response()->json([
                    'success' => false, 
                    'message' => 'You are outside the permitted work location radius. Distance: ' . round($distance) . 'm'
                ], 403);
            }
        }

        $today = Carbon::today();
        
        // Find if there's already an attendance record for today
        $attendance = Attendance::where('worker_id', $worker->id)->whereDate('date', $today)->first();

        if (!$attendance) {
            // Clock In
            $isLate = false;
            if ($worker->shift && $worker->shift->start_time) {
                $shiftStart = Carbon::parse($worker->shift->start_time);
                $graceEnd = $shiftStart->copy()->addMinutes($worker->shift->grace_period_minutes);
                if (now()->format('H:i:s') > $graceEnd->format('H:i:s')) {
                    $isLate = true;
                }
            }

            $attendance = Attendance::create([
                'worker_id' => $worker->id,
                'date' => $today,
                'check_in_at' => now(),
                'status' => $isLate ? 'late' : 'present',
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'selfie_in' => $request->selfie,
                'ip_in' => $request->ip(),
                'device_in' => $request->userAgent(),
            ]);

            // Real-time Filament Notification to Admins
            Notification::make()
                ->title("{$worker->name} Clocked In")
                ->body($isLate ? "Arrived late." : "Arrived on time.")
                ->success()
                ->sendToDatabase(\App\Models\User::where('role', 'admin')->orWhere('is_admin', true)->get());

            return response()->json([
                'success' => true,
                'action' => 'check_in',
                'worker' => $worker->name,
                'message' => "Welcome, {$worker->name}. Check-in successful!"
            ]);

        } else if (!$attendance->check_out_at) {
            // Clock Out
            $checkInTime = Carbon::parse($attendance->check_in_at);
            $totalHours = $checkInTime->diffInMinutes(now()) / 60;

            $attendance->update([
                'check_out_at' => now(),
                'total_hours' => round($totalHours, 2),
                'latitude_out' => $request->latitude,
                'longitude_out' => $request->longitude,
                'selfie_out' => $request->selfie,
                'ip_out' => $request->ip(),
                'device_out' => $request->userAgent(),
            ]);

            // Real-time Filament Notification to Admins
            Notification::make()
                ->title("{$worker->name} Clocked Out")
                ->body("Worked " . round($totalHours, 2) . " hours today.")
                ->info()
                ->sendToDatabase(\App\Models\User::where('role', 'admin')->orWhere('is_admin', true)->get());

            return response()->json([
                'success' => true,
                'action' => 'check_out',
                'worker' => $worker->name,
                'message' => "Goodbye, {$worker->name}. Check-out successful!"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "{$worker->name}, you have already checked out for today!"
            ], 422);
        }
    }

    // Haversine formula to calculate distance in meters
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }
}
