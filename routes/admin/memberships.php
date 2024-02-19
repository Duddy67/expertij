<?php

use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\Membership\SettingController as AdminMembershipSettingController;
use App\Http\Controllers\Admin\Membership\SharingController as AdminMembershipSharingController;

// Settings
Route::get('/memberships/settings', [AdminMembershipSettingController::class, 'index'])->name('admin.memberships.settings.index');
Route::patch('/memberships/settings', [AdminMembershipSettingController::class, 'update'])->name('admin.memberships.settings.update');
// Sharings
Route::delete('/memberships/sharings', [AdminMembershipSharingController::class, 'massDestroy'])->name('admin.memberships.sharings.massDestroy');
Route::get('/memberships/sharings/cancel/{sharing?}', [AdminMembershipSharingController::class, 'cancel'])->name('admin.memberships.sharings.cancel');
Route::put('/memberships/sharings/checkin', [AdminMembershipSharingController::class, 'massCheckIn'])->name('admin.memberships.sharings.massCheckIn');
Route::put('/memberships/sharings/publish', [AdminMembershipSharingController::class, 'massPublish'])->name('admin.memberships.sharings.massPublish');
Route::put('/memberships/sharings/unpublish', [AdminMembershipSharingController::class, 'massUnpublish'])->name('admin.memberships.sharings.massUnpublish');
Route::post('/memberships/sharings/document', [AdminMembershipSharingController::class, 'addDocument'])->name('admin.memberships.sharings.document.add');
Route::put('/memberships/sharings/document/{document}', [AdminMembershipSharingController::class, 'replaceDocument'])->name('admin.memberships.sharings.document.replace');
Route::delete('/memberships/sharings/document/{document}', [AdminMembershipSharingController::class, 'deleteDocument'])->name('admin.memberships.sharings.document.delete');
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
