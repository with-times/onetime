<?php

use App\Models\Web\WebSite;
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
        Schema::create('subscribes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WebSite::class)->comment('所属站点');
            $table->foreignIdFor(\App\Models\User::class)->comment('所属用户');
            $table->string('title')->nullable(); // 标题
            $table->text('description')->nullable(); // 描述
            $table->dateTime('last_modified')->nullable(); // 发布日期
            $table->text('link')->nullable(); // 链接
            $table->text('authors')->nullable(); // 作者
            $table->string('uuid')->nullable(); // 唯一ID
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
        Schema::dropIfExists('subscribes');
    }
};
