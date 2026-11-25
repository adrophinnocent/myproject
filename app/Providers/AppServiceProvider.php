<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Auto Schema Migrations Guard for Live Environment
        try {
            if (! Schema::hasTable('accommodations')) {
                Schema::create('accommodations', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->unique();
                    $table->string('type')->default('lodge');
                    $table->string('category_level')->default('standard');
                    $table->unsignedBigInteger('destination_id')->nullable();
                    $table->string('location_name')->nullable();
                    $table->text('description')->nullable();
                    $table->json('images')->nullable();
                    $table->decimal('star_rating', 3, 1)->default(4.0);
                    $table->decimal('supplier_cost_per_night', 10, 2)->nullable();
                    $table->json('amenities')->nullable();
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('room_types')) {
                Schema::create('room_types', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug')->unique();
                    $table->string('description')->nullable();
                    $table->integer('max_occupants')->default(2);
                    $table->decimal('default_supplement_price', 10, 2)->default(0.00);
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('tour_packages')) {
                Schema::create('tour_packages', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('tour_id')->nullable();
                    $table->unsignedBigInteger('safari_id')->nullable();
                    $table->string('name');
                    $table->string('slug')->nullable();
                    $table->text('description')->nullable();
                    $table->decimal('selling_price', 10, 2)->default(0.00);
                    $table->decimal('adult_price', 10, 2)->nullable();
                    $table->decimal('child_price', 10, 2)->nullable();
                    $table->decimal('single_supplement', 10, 2)->default(0.00);
                    $table->decimal('deposit_percentage', 5, 2)->default(20.00);
                    $table->string('currency', 10)->default('USD');
                    $table->json('images')->nullable();
                    $table->integer('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->decimal('supplier_hotel_cost', 10, 2)->default(0.00);
                    $table->decimal('park_fees_cost', 10, 2)->default(0.00);
                    $table->decimal('transport_cost', 10, 2)->default(0.00);
                    $table->decimal('guide_cost', 10, 2)->default(0.00);
                    $table->decimal('meals_cost', 10, 2)->default(0.00);
                    $table->decimal('transfers_cost', 10, 2)->default(0.00);
                    $table->decimal('other_costs', 10, 2)->default(0.00);
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('package_itinerary_days')) {
                Schema::create('package_itinerary_days', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('tour_package_id');
                    $table->integer('day_number')->default(1);
                    $table->string('title')->nullable();
                    $table->text('description')->nullable();
                    $table->unsignedBigInteger('accommodation_id')->nullable();
                    $table->text('accommodation_notes')->nullable();
                    $table->string('meals')->nullable();
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('package_room_options')) {
                Schema::create('package_room_options', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('tour_package_id');
                    $table->unsignedBigInteger('room_type_id');
                    $table->decimal('supplement_price', 10, 2)->default(0.00);
                    $table->boolean('is_available')->default(true);
                    $table->timestamps();
                });
            }

            if (Schema::hasTable('faqs') && ! Schema::hasColumn('faqs', 'tour_id')) {
                Schema::table('faqs', function ($table) {
                    if (! Schema::hasColumn('faqs', 'tour_id')) {
                        $table->unsignedBigInteger('tour_id')->nullable();
                    }
                    if (! Schema::hasColumn('faqs', 'destination_id')) {
                        $table->unsignedBigInteger('destination_id')->nullable();
                    }
                    if (! Schema::hasColumn('faqs', 'category_id')) {
                        $table->unsignedBigInteger('category_id')->nullable();
                    }
                    if (! Schema::hasColumn('faqs', 'primary_keyword')) {
                        $table->string('primary_keyword')->nullable();
                    }
                    if (! Schema::hasColumn('faqs', 'is_verified_traveler')) {
                        $table->boolean('is_verified_traveler')->default(true);
                    }
                });
            }

            if (Schema::hasTable('bookings')) {
                Schema::table('bookings', function ($table) {
                    if (! Schema::hasColumn('bookings', 'tour_package_id')) {
                        $table->unsignedBigInteger('tour_package_id')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'room_type_id')) {
                        $table->unsignedBigInteger('room_type_id')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'package_name')) {
                        $table->string('package_name')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'room_type_name')) {
                        $table->string('room_type_name')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'room_supplement_amount')) {
                        $table->decimal('room_supplement_amount', 10, 2)->default(0.00);
                    }
                    if (! Schema::hasColumn('bookings', 'selected_hotels_summary')) {
                        $table->json('selected_hotels_summary')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'token')) {
                        $table->string('token', 64)->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'country')) {
                        $table->string('country', 100)->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'currency')) {
                        $table->string('currency', 10)->default('USD');
                    }
                    if (! Schema::hasColumn('bookings', 'deposit_percentage')) {
                        $table->decimal('deposit_percentage', 5, 2)->default(20.00);
                    }
                    if (! Schema::hasColumn('bookings', 'deposit_required')) {
                        $table->decimal('deposit_required', 10, 2)->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'deposit_paid')) {
                        $table->decimal('deposit_paid', 10, 2)->default(0.00);
                    }
                    if (! Schema::hasColumn('bookings', 'balance_amount')) {
                        $table->decimal('balance_amount', 10, 2)->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'balance_due_date')) {
                        $table->date('balance_due_date')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'approved_at')) {
                        $table->timestamp('approved_at')->nullable();
                    }
                    if (! Schema::hasColumn('bookings', 'cancellation_reason')) {
                        $table->text('cancellation_reason')->nullable();
                    }
                });
            }

            if (Schema::hasTable('users')) {
                if (! Schema::hasColumn('users', 'is_admin')) {
                    Schema::table('users', function ($table) {
                        $table->boolean('is_admin')->default(false)->after('email_verified_at');
                    });
                }

                User::whereIn('email', [
                    'twinasafaris@gmail.com',
                    'admin@twinasafaris.com',
                    'info@twinasafaris.com',
                    'admin@tourism.com',
                    'bellainnos@gmail.com',
                ])->where('is_admin', false)->update(['is_admin' => true]);
            }

            if (! Schema::hasTable('booking_payments')) {
                Schema::create('booking_payments', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('booking_id');
                    $table->string('transaction_reference')->unique();
                    $table->string('gateway');
                    $table->string('payment_type');
                    $table->decimal('amount', 10, 2);
                    $table->string('currency', 10)->default('USD');
                    $table->string('status')->default('pending');
                    $table->string('payer_name')->nullable();
                    $table->string('payer_email')->nullable();
                    $table->string('gateway_reference')->nullable();
                    $table->text('gateway_response')->nullable();
                    $table->text('notes')->nullable();
                    $table->timestamp('paid_at')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            // Ignore if schema checks fail
        }

        // Prevent N+1 queries during development
        Model::preventLazyLoading(! app()->isProduction());
    }
}
