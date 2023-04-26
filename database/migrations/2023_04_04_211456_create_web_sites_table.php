<?php

use App\Enums\WebSite;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->comment('所属用户');
            $table->string('title')->comment('站点标题');
            $table->string('url')->comment('站点链接');
            $table->string('feed')->nullable()->comment('站点feed链接');
            $table->enum('state',[WebSite::NORMAL, WebSite::WEB_ERROR, WebSite::RSS_ERROR])->default(Website::NORMAL)->comment('站点状态');
            $table->string('site_number')->comment('编号');
            $table->string('uuid')->comment('唯一ID');
            $table->text('site_message')->nullable()->comment('站长有话说');
            $table->timestamp('last_updated_at')->nullable()->comment('最后更新时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_sites');
    }
};
