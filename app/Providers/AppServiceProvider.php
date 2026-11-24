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

            // Self-heal the primary admin account: production databases have
            // been seen without is_admin set, which sends the user back to the
            // homepage after a successful login. Keep the known admin emails
            // working without requiring manual tinker/SQL access on the host.
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
