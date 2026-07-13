<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\WebContentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

// Free routes
Route::get('/', [App\Http\Controllers\WelcomeController::class, '__invoke']);

Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');
Route::get('/unsubscribe/{email}', [SubscriberController::class, 'unsubscribe'])->name('unsubscribe');

Route::get('/skills', [SkillController::class, 'publicIndex'])->name('skills');
Route::get('/journey', [ExperienceController::class, 'publicIndex'])->name('journey');
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/folder/{galleryFolder}', [App\Http\Controllers\GalleryFolderController::class, 'publicShow'])->name('gallery.folder');
Route::get('/about', [ProfileController::class, 'publicAbout'])->name('about');
Route::get('/services', function () { return view('services'); })->name('services');
Route::get('/projects', [ProjectController::class, 'publicIndex'])->name('projects');
Route::get('/connect', function () { return view('connect'); })->name('connect');
Route::post('/connect', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::get('/edu&certs', [EducationController::class, 'publicIndex'])->name('edu&certs');
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
Route::get('/news', [BlogPostController::class, 'publicIndex'])->name('blog');
Route::get('/news/{slug}', [BlogPostController::class, 'publicShow'])->name('blog.post');

//Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/request-code', [AuthController::class, 'requestAccessCode'])->name('login.request-code');
Route::post('/login/verify', [AuthController::class, 'verifyAndLogin'])->name('login.verify');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'overview'])->name('overview');

    // Messages
    Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages');
    Route::get('/messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{contactMessage}/read', [ContactMessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

    // About Me / Profile
    Route::get('/about-me', [ProfileController::class, 'edit'])->name('about-me');
    Route::put('/about-me', [ProfileController::class, 'update'])->name('about-me.update');

    // Web Content
    Route::get('/web', [WebContentController::class, 'edit'])->name('web');
    Route::put('/web', [WebContentController::class, 'update'])->name('web.update');

    // Skills
    Route::get('/skills', [SkillController::class, 'index'])->name('skills');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::post('/skills/bulk', [SkillController::class, 'storeBulk'])->name('skills.store-bulk');
    Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Certifications
    Route::get('/certifications', [CertificationController::class, 'index'])->name('certifications');
    Route::post('/certifications', [CertificationController::class, 'store'])->name('certifications.store');
    Route::put('/certifications/{certification}', [CertificationController::class, 'update'])->name('certifications.update');
    Route::delete('/certifications/{certification}', [CertificationController::class, 'destroy'])->name('certifications.destroy');

    // Awards
    Route::get('/awards', [AwardController::class, 'index'])->name('awards');
    Route::post('/awards', [AwardController::class, 'store'])->name('awards.store');
    Route::put('/awards/{award}', [AwardController::class, 'update'])->name('awards.update');
    Route::delete('/awards/{award}', [AwardController::class, 'destroy'])->name('awards.destroy');

    // My Files / Documents
    Route::get('/my-files', [DocumentController::class, 'index'])->name('my-files');
    Route::post('/my-files', [DocumentController::class, 'store'])->name('my-files.store');
    Route::put('/my-files/{document}', [DocumentController::class, 'update'])->name('my-files.update');
    Route::delete('/my-files/{document}', [DocumentController::class, 'destroy'])->name('my-files.destroy');

    // Blog Posts
    Route::get('/blog-posts', [BlogPostController::class, 'index'])->name('blog-posts');
    Route::post('/blog-posts', [BlogPostController::class, 'store'])->name('blog-posts.store');
    Route::put('/blog-posts/{blogPost}', [BlogPostController::class, 'update'])->name('blog-posts.update');
    Route::delete('/blog-posts/{blogPost}', [BlogPostController::class, 'destroy'])->name('blog-posts.destroy');

    // Education
    Route::get('/education', [EducationController::class, 'index'])->name('education');
    Route::post('/education', [EducationController::class, 'store'])->name('education.store');
    Route::put('/education/{education}', [EducationController::class, 'update'])->name('education.update');
    Route::delete('/education/{education}', [EducationController::class, 'destroy'])->name('education.destroy');

    // Journey / Experience
    Route::get('/journey', [ExperienceController::class, 'index'])->name('journey');
    Route::post('/journey', [ExperienceController::class, 'store'])->name('journey.store');
    Route::put('/journey/{experience}', [ExperienceController::class, 'update'])->name('journey.update');
    Route::delete('/journey/{experience}', [ExperienceController::class, 'destroy'])->name('journey.destroy');

    // Gallery
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/gallery/{galleryItem}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{galleryItem}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    // Gallery Folders
    Route::post('/gallery-folders', [App\Http\Controllers\GalleryFolderController::class, 'store'])->name('gallery-folders.store');
    Route::get('/gallery-folders/{galleryFolder}', [App\Http\Controllers\GalleryFolderController::class, 'show'])->name('gallery-folders.show');
    Route::put('/gallery-folders/{galleryFolder}', [App\Http\Controllers\GalleryFolderController::class, 'update'])->name('gallery-folders.update');
    Route::post('/gallery-folders/{galleryFolder}/update', [App\Http\Controllers\GalleryFolderController::class, 'update'])->name('gallery-folders.update-post');
    Route::delete('/gallery-folders/{galleryFolder}', [App\Http\Controllers\GalleryFolderController::class, 'destroy'])->name('gallery-folders.destroy');

    // Document Activity Log
    Route::get('/activity/document-activity', [ActivityLogController::class, 'index'])->name('document-activity');
    Route::post('/activity/document-activity/{activityLog}/read', [ActivityLogController::class, 'markAsRead'])->name('document-activity.read');
    Route::post('/activity/document-activity/read-all', [ActivityLogController::class, 'markAllAsRead'])->name('document-activity.read-all');
    Route::delete('/activity/document-activity/{activityLog}', [ActivityLogController::class, 'destroy'])->name('document-activity.destroy');

    Route::get('/activity/web-admin', [AdminController::class, 'webAdmin'])->name('web-admin');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/blog-settings', [SettingController::class, 'blogSettings'])->name('blog-settings');
    Route::post('/settings/bulk', [SettingController::class, 'bulkUpdate'])->name('settings.bulk');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');

    // Subscribers
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
});

