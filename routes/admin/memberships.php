<?php

use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\Membership\SettingController as AdminMembershipSettingController;

// Settings
Route::get('/memberships/settings', [AdminMembershipSettingController::class, 'index'])->name('admin.memberships.settings.index');
Route::patch('/memberships/settings', [AdminMembershipSettingController::class, 'update'])->name('admin.memberships.settings.update');
// Membershipss
Route::delete('/memberships', [AdminMembershipController::class, 'massDestroy'])->name('admin.memberships.massDestroy');
Route::get('/memberships/cancel/{memberships?}', [AdminMembershipController::class, 'cancel'])->name('admin.memberships.cancel');
Route::put('/memberships/checkin', [AdminMembershipController::class, 'massCheckIn'])->name('admin.memberships.massCheckIn');
Route::put('/memberships/publish', [AdminMembershipController::class, 'massPublish'])->name('admin.memberships.massPublish');
Route::put('/memberships/unpublish', [AdminMembershipController::class, 'massUnpublish'])->name('admin.memberships.massUnpublish');
Route::resource('memberships', AdminMembershipController::class, ['as' => 'admin'])->except(['show']);
