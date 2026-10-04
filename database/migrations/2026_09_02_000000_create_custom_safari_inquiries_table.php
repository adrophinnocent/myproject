<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_safari_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country')->nullable();

            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->string('children_ages')->nullable();

            $table->date('travel_date')->nullable();
            $table->boolean('flexible_dates')->default(false);
            $table->integer('duration_days')->default(7);

            $table->string('trip_type')->nullable(); // Safari, Kilimanjaro, Zanzibar, Beach, Combination
            $table->string('accommodation_preference')->nullable();
            $table->string('budget_per_person')->nullable();
            $table->string('travel_style')->nullable();
            $table->string('group_type')->nullable(); // Honeymoon, Couple, Family, Solo, Friends, Group

            $table->json('activities')->nullable();
            $table->text('special_requests')->nullable();

            $table->string('status')->default('new'); // new, reviewed, converted, closed
            $table->foreignId('proposal_id')->nullable()->constrained('proposals')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->string('reference_code')->nullable()->after('token');
            $table->integer('version')->default(1)->after('reference_code');

            $table->string('subtitle')->nullable()->after('title');
            $table->string('country')->nullable()->after('client_phone');
            $table->integer('duration_nights')->default(0)->after('duration_days');

            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->json('destinations')->nullable();
            $table->string('safari_style')->nullable();
            $table->string('accommodation_level')->nullable();
            $table->string('transport_type')->nullable();
            $table->boolean('is_private')->default(true);

            $table->json('highlights')->nullable();
            $table->json('optional_extras')->nullable();
            $table->text('cancellation_policy')->nullable();

            $table->foreignId('custom_safari_inquiry_id')->nullable()->constrained('custom_safari_inquiries')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['custom_safari_inquiry_id']);
            $table->dropColumn([
                'reference_code', 'version', 'subtitle', 'country', 'duration_nights',
                'start_location', 'end_location', 'destinations', 'safari_style',
                'accommodation_level', 'transport_type', 'is_private', 'highlights',
                'optional_extras', 'cancellation_policy', 'custom_safari_inquiry_id'
            ]);
        });

        Schema::dropIfExists('custom_safari_inquiries');
    }
};
