@extends('admin.layouts.app')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings</h5>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Company Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="company_name">Company Name</label>
                                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ $settings['company_name'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_phone">Company Phone</label>
                                <input type="text" id="company_phone" name="company_phone" class="form-control" value="{{ $settings['company_phone'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_email">Company Email</label>
                                <input type="email" id="company_email" name="company_email" class="form-control" value="{{ $settings['company_email'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_address">Company Address</label>
                                <textarea id="company_address" name="company_address" class="form-control" rows="2">{{ $settings['company_address'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notification Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms_enabled" name="sms_enabled" value="1" {{ isset($settings['sms_enabled']) && $settings['sms_enabled'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_enabled">SMS Notifications</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="whatsapp_enabled" name="whatsapp_enabled" value="1" {{ isset($settings['whatsapp_enabled']) && $settings['whatsapp_enabled'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="whatsapp_enabled">WhatsApp Notifications</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_enabled" name="email_enabled" value="1" {{ isset($settings['email_enabled']) && $settings['email_enabled'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_enabled">Email Notifications</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">WhatsApp Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="whatsapp_api_key">WhatsApp API Key</label>
                                <input type="password" id="whatsapp_api_key" name="whatsapp_api_key" class="form-control" value="{{ $settings['whatsapp_api_key'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="whatsapp_phone_number">WhatsApp Phone Number</label>
                                <input type="text" id="whatsapp_phone_number" name="whatsapp_phone_number" class="form-control" value="{{ $settings['whatsapp_phone_number'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">SMS Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="sms_api_key">SMS API Key</label>
                                <input type="password" id="sms_api_key" name="sms_api_key" class="form-control" value="{{ $settings['sms_api_key'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="sms_provider">SMS Provider</label>
                                <input type="text" id="sms_provider" name="sms_provider" class="form-control" value="{{ $settings['sms_provider'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Email (SMTP) Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="smtp_host">SMTP Host</label>
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="{{ $settings['smtp_host'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="smtp_port">SMTP Port</label>
                                <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="{{ $settings['smtp_port'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="smtp_username">SMTP Username</label>
                                <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="{{ $settings['smtp_username'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="smtp_password">SMTP Password</label>
                                <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="{{ $settings['smtp_password'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="smtp_encryption">SMTP Encryption</label>
                                <select id="smtp_encryption" name="smtp_encryption" class="form-select">
                                    <option value="tls" {{ isset($settings['smtp_encryption']) && $settings['smtp_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ isset($settings['smtp_encryption']) && $settings['smtp_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ isset($settings['smtp_encryption']) && $settings['smtp_encryption'] == '' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Save Settings</button>
            </div>
        </div>
    </form>
</div>
@endsection
