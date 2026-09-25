<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // 2. Branches
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('radius_meters')->default(200);
            $table->timestamps();
        });

        // 3. Shifts
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('grace_period_minutes')->default(15);
            $table->timestamps();
        });

        // 4. Modify users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('is_admin');
        });

        // 5. Modify workers table
        Schema::table('workers', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete()->after('id');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->after('company_id');
            $table->string('department')->nullable()->after('position');
            $table->string('profile_picture')->nullable()->after('email');
            $table->string('status')->default('active')->after('qr_token'); // active, deactivated
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete()->after('status');
            $table->integer('leave_balance_vacation')->default(21)->after('shift_id');
            $table->integer('leave_balance_sick')->default(10)->after('leave_balance_vacation');
        });

        // 6. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->string('status')->default('absent'); // present, late, absent, on_leave
            $table->decimal('total_hours', 5, 2)->default(0.00);
            $table->decimal('latitude_in', 10, 8)->nullable();
            $table->decimal('longitude_in', 11, 8)->nullable();
            $table->decimal('latitude_out', 10, 8)->nullable();
            $table->decimal('longitude_out', 11, 8)->nullable();
            $table->longText('selfie_in')->nullable(); // Can store base64 or path
            $table->longText('selfie_out')->nullable();
            $table->string('ip_in')->nullable();
            $table->string('ip_out')->nullable();
            $table->string('device_in')->nullable();
            $table->string('device_out')->nullable();
            $table->timestamps();

            // Prevent duplicate daily records per worker
            $table->unique(['worker_id', 'date']);
        });

        // 7. Leave Requests
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->string('type'); // vacation, sick, personal
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // 8. Fleet Vehicles
        Schema::create('fleet_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('registration_number')->unique();
            $table->integer('year');
            $table->string('status')->default('available'); // available, in_use, maintenance, out_of_service
            $table->foreignId('assigned_driver_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 9. Vehicle Assignments
        Schema::create('vehicle_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('fleet_vehicles')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('workers')->cascadeOnDelete();
            $table->dateTime('assigned_at');
            $table->dateTime('returned_at')->nullable();
            $table->string('purpose')->nullable();
            $table->integer('start_mileage')->nullable();
            $table->integer('end_mileage')->nullable();
            $table->timestamps();
        });

        // 10. Vehicle Maintenances
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('fleet_vehicles')->cascadeOnDelete();
            $table->string('type'); // routine, repair, inspection, insurance
            $table->text('description');
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('insurance_expiration_date')->nullable();
            $table->date('technical_inspection_date')->nullable();
            $table->timestamps();
        });

        // 11. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('vehicle_maintenances');
        Schema::dropIfExists('vehicle_assignments');
        Schema::dropIfExists('fleet_vehicles');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('attendances');
        
        Schema::table('workers', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['shift_id']);
            $table->dropColumn([
                'company_id', 'branch_id', 'department', 'profile_picture', 
                'status', 'shift_id', 'leave_balance_vacation', 'leave_balance_sick'
            ]);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::dropIfExists('shifts');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('companies');
    }
};
