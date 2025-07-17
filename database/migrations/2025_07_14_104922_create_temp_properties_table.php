<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // posted by

            $table->enum('listing_type', ['sell', 'rent']);
            $table->foreignId('property_category_id')->constrained('property_categories');

            $table->decimal('covered_area', 10, 2)->nullable();
            $table->decimal('carpet_area', 10, 2)->nullable();

            $table->decimal('total_price', 15, 2);
            $table->boolean('is_price_negotiable')->default(false);

            $table->string('city')->nullable();
            $table->string('locality')->nullable();
            $table->string('project_name')->nullable(); // society / project
            $table->text('address')->nullable();
            $table->integer('total_numbers')->nullable(); // like total flats or total units

            $table->tinyInteger('bedrooms_count')->nullable();
            $table->tinyInteger('bathroom_count')->nullable();
            $table->tinyInteger('balcony_count')->nullable();

            $table->tinyInteger('floor_no')->nullable();
            $table->tinyInteger('total_floors')->nullable();

            $table->tinyInteger('number_of_open_side')->nullable(); // open sides
            $table->string('width_of_road_facing_plot')->nullable();

            $table->boolean('is_furnishing')->nullable();
            $table->tinyInteger('floor_allowed_for_construction')->nullable();

            $table->boolean('boundary_wall_made')->nullable();
            $table->boolean('any_construction_done')->nullable();
            $table->boolean('is_colony_have_gate')->nullable();
            $table->boolean('personal_washroom')->nullable();
            $table->boolean('is_main_road_facing')->nullable();
            $table->string('near_by_business')->nullable();
            $table->boolean('pantry_cafeteria')->nullable();

            $table->json('additional_rooms')->nullable();   // ex: ["pooja room", "study"]
            $table->json('overlooking')->nullable();        // ex: ["garden", "park"]
            $table->string('directional_facing')->nullable(); // ex: east, west, etc.
            $table->string('ownership_type')->nullable();   // owned, lease, etc.
            $table->text('more_property_details')->nullable();
            $table->string('transaction_type')->nullable(); // new / resale
            $table->enum('availability_status', ['under_construction', 'ready_to_move'])->nullable();
            $table->string('possession_by')->nullable();    // year or date
            $table->string('approved_by_bank')->nullable(); // bank name or “multiple”

            $table->json('amenities')->nullable();          // ["lift", "parking"]
            $table->string('flooring_type')->nullable();
            $table->string('landmark')->nullable();

            $table->json('gallery_images')->nullable();     // [img1.jpg, img2.jpg, ...]

            $table->boolean('is_featured')->default(false);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expired_at')->nullable();
            $table->bigInteger('views_count')->default(0);

            $table->enum('status', ['active', 'pending', 'sold', 'rented'])->default('pending');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_properties');
    }
};
