<?php

use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\Membership\SettingController as AdminMembershipSettingController;
use App\Http\Controllers\Admin\Membership\SharingController as AdminMembershipSharingController;

// Settings
Route::get('/memberships/settings', [AdminMembershipSettingController::class, 'index'])->name('admin.memberships.settings.index');
Route::patch('/memberships/settings', [AdminMembershipSettingController::class, 'update'])->name('admin.memberships.settings.update');
// Sharings
Route::resource('/memberships/sharings', AdminMembershipSharingController::class, ['as' => 'admin.memberships'])->except(['show']);
// Memberships
Route::delete('/memberships', [AdminMembershipController::class, 'massDestroy'])->name('admin.memberships.massDestroy');
Route::get('/memberships/cancel/{membership?}', [AdminMembershipController::class, 'cancel'])->name('admin.memberships.cancel');
Route::put('/memberships/checkin', [AdminMembershipController::class, 'massCheckIn'])->name('admin.memberships.massCheckIn');
Route::put('/memberships/publish', [AdminMembershipController::class, 'massPublish'])->name('admin.memberships.massPublish');
Route::put('/memberships/unpublish', [AdminMembershipController::class, 'massUnpublish'])->name('admin.memberships.massUnpublish');
Route::get('/memberships/check-renewal', [AdminMembershipController::class, 'checkRenewal'])->name('admin.memberships.checkRenewal');
Route::put('/memberships/send-emails/{membership}', [AdminMembershipController::class, 'sendEmails'])->name('admin.memberships.sendEmails');
Route::put('/memberships/payment/{membership}', [AdminMembershipController::class, 'setPayment'])->name('admin.memberships.setPayment');
Route::resource('memberships', AdminMembershipController::class, ['as' => 'admin'])->except(['show']);
