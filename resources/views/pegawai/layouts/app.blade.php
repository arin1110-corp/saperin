<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SAMPERIN')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link type="favicon" href="{{ asset('assets/images/logo-samperin.png') }}" rel="shortcut icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --samperin-navy: #101b4d;
            --samperin-blue: #1677ff;
            --samperin-blue-soft: #edf5ff;
            --samperin-text: #405a82;
            --samperin-border: #dfe8f3;
            --samperin-bg: #f4f9ff;
            --samperin-green: #16a66a;
            --samperin-orange: #f28c28;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--samperin-bg);
            color: var(--samperin-navy);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .samperin-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .samperin-main {
            flex: 1;
            padding: 28px 0 34px;
        }

        .samperin-container {
            width: min(1430px, calc(100% - 48px));
            margin: 0 auto;
        }

        /* =========================
           HEADER
        ========================= */

        .samperin-header {
            height: 82px;
            background: #fff;
            border-bottom: 1px solid #e2ebf5;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .samperin-header-inner {
            height: 100%;
            width: min(1430px, calc(100% - 48px));
            margin: 0 auto;
            display: flex;
            align-items: center;
        }

        .samperin-brand {
            display: flex;
            align-items: center;
        }

        .samperin-brand-logo {
            height: 62px;
            object-fit: contain;
            margin-right: 10px;
        }

        .samperin-brand-name {
            color: var(--samperin-navy);
            font-size: 21px;
            font-weight: 800;
            line-height: 1.1;
        }

        .samperin-brand-subtitle {
            color: #0067d9;
            font-size: 13px;
            line-height: 1.35;
            margin-top: 2px;
        }

        .samperin-brand-office {
            color: #0067d9;
            font-size: 12px;
            line-height: 1.2;
        }

        .samperin-header-nav {
            flex: 1;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 8px;
        }

        .samperin-nav-item {
            min-width: 100px;
            height: 100%;
            padding: 0 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            color: #4c6384;
            font-size: 16px;
            font-weight: 600;
            border-bottom: 4px solid transparent;
            transition: .2s ease;
        }

        .samperin-nav-item i {
            font-size: 22px;
        }

        .samperin-nav-item:hover {
            color: var(--samperin-blue);
        }

        .samperin-nav-item.active {
            color: var(--samperin-blue);
            border-bottom-color: var(--samperin-blue);
        }

        .samperin-account {
            min-width: 300px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 16px;
        }

        .samperin-notification {
            position: relative;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #526b8d;
            font-size: 23px;
        }

        .samperin-notification-dot {
            position: absolute;
            top: 4px;
            right: 3px;
            width: 11px;
            height: 11px;
            background: #f00000;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .samperin-user-menu {
            border: 0;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--samperin-navy);
            padding: 5px 0;
        }

        .samperin-user-photo {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d9e3ef;
            background: #edf3f8;
        }

        .samperin-user-photo-placeholder {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #edf3f8;
            color: #6c7f99;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .samperin-user-info {
            text-align: left;
            line-height: 1.25;
            max-width: 175px;
        }

        .samperin-user-name {
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .samperin-user-role {
            color: #637794;
            font-size: 12px;
            margin-top: 3px;
        }

        .samperin-user-chevron {
            color: #3f5c81;
            margin-left: 4px;
        }

        /* =========================
           BREADCRUMB
        ========================= */

        .samperin-breadcrumb {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #506a8d;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .samperin-breadcrumb i {
            font-size: 16px;
        }

        .samperin-breadcrumb .separator {
            color: #8ca0ba;
        }

        .samperin-breadcrumb .current {
            color: var(--samperin-navy);
            font-weight: 700;
        }

        .samperin-page-title {
            margin-bottom: 20px;
        }

        .samperin-page-title h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -.7px;
            color: var(--samperin-navy);
        }

        .samperin-page-title p {
            margin: 4px 0 0;
            color: #526b8d;
            font-size: 15px;
        }

        /* =========================
           HERO PROFILE
        ========================= */

        .samperin-profile-hero {
            position: relative;
            min-height: 228px;
            overflow: hidden;
            background:
                linear-gradient(90deg,
                    rgba(255, 255, 255, .99) 0%,
                    rgba(255, 255, 255, .96) 38%,
                    rgba(255, 255, 255, .70) 65%,
                    rgba(223, 241, 255, .72) 100%),
                url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1800&q=85') center/cover;
            border: 1px solid #dce7f2;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(24, 58, 98, .05);
            display: flex;
            align-items: center;
            padding: 18px;
        }

        .samperin-profile-hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr) 270px;
            gap: 25px;
            align-items: center;
        }

        .samperin-profile-photo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            align-self: stretch;
            justify-content: center;
        }

        .samperin-profile-photo {
            width: 162px;
            height: 186px;
            border-radius: 15px;
            object-fit: cover;
            object-position: center top;
            background: #edf2f7;
            border: 1px solid #dbe5ef;
            box-shadow: 0 3px 10px rgba(24, 48, 79, .08);
        }

        .samperin-profile-placeholder {
            width: 162px;
            height: 186px;
            border-radius: 15px;
            background: #edf2f7;
            border: 1px solid #dbe5ef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8ca0b8;
            font-size: 48px;
        }

        .samperin-photo-button {
            margin-top: -1px;
            width: 162px;
            height: 38px;
            border: 1px solid var(--samperin-blue);
            background: #fff;
            color: var(--samperin-blue);
            border-radius: 0 0 7px 7px;
            font-size: 13px;
            font-weight: 700;
        }

        .samperin-photo-button:hover {
            background: var(--samperin-blue-soft);
        }

        .samperin-profile-name {
            font-size: 28px;
            line-height: 1.2;
            font-weight: 800;
            color: var(--samperin-navy);
            margin-bottom: 7px;
        }

        .samperin-profile-position {
            color: #456083;
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .samperin-profile-office {
            color: #456083;
            font-size: 16px;
        }

        .samperin-profile-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 17px;
        }

        .samperin-badge {
            min-height: 36px;
            padding: 8px 13px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            font-size: 13px;
            font-weight: 600;
        }

        .samperin-badge-nip {
            background: rgba(255, 248, 237, .95);
            border: 1px solid #f2dfc0;
            color: #815b25;
        }

        .samperin-badge-active {
            background: rgba(231, 250, 241, .95);
            border: 1px solid #a6e5c7;
            color: #07965a;
        }

        .samperin-quote {
            align-self: stretch;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 15px 5px 15px 15px;
            color: var(--samperin-navy);
        }

        .samperin-quote-text {
            font-size: 16px;
            font-style: italic;
            line-height: 1.55;
            font-weight: 500;
        }

        .samperin-quote-line {
            margin-top: 18px;
            width: 72px;
            height: 3px;
            background: var(--samperin-blue);
        }

        /* =========================
           LOWER CONTENT
        ========================= */

        .samperin-content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 365px;
            gap: 16px;
            margin-top: 16px;
        }

        .samperin-main-card,
        .samperin-request-card {
            background: #fff;
            border: 1px solid #dfe8f2;
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(29, 65, 106, .045);
        }

        /* =========================
           PROFILE TABS
        ========================= */

        .samperin-tabs {
            display: flex;
            align-items: stretch;
            overflow-x: auto;
            border-bottom: 1px solid #dfe7f1;
            padding: 0 14px;
            scrollbar-width: none;
        }

        .samperin-tabs::-webkit-scrollbar {
            display: none;
        }

        .samperin-tab {
            flex: 0 0 auto;
            height: 59px;
            padding: 0 17px;
            display: flex;
            align-items: center;
            gap: 9px;
            border: 0;
            border-bottom: 4px solid transparent;
            background: transparent;
            color: #536b8c;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
        }

        .samperin-tab i {
            font-size: 19px;
        }

        .samperin-tab.active {
            color: var(--samperin-blue);
            font-weight: 700;
            border-bottom-color: var(--samperin-blue);
        }

        .samperin-tab-content {
            padding: 20px 15px 24px;
        }

        .samperin-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .samperin-info-box {
            border: 1px solid #dfe8f1;
            border-radius: 9px;
            overflow: hidden;
        }

        .samperin-info-header {
            min-height: 48px;
            background: linear-gradient(90deg, #f5f9fd, #fbfdff);
            border-bottom: 1px solid #e2eaf3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
        }

        .samperin-info-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--samperin-navy);
            font-size: 14px;
            font-weight: 700;
        }

        .samperin-info-title i {
            color: var(--samperin-blue);
            font-size: 19px;
        }

        .samperin-edit-button {
            border: 0;
            background: #e9f3ff;
            color: var(--samperin-blue);
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .samperin-edit-button:hover {
            background: #dcecff;
        }

        .samperin-info-body {
            padding: 4px 15px 8px;
        }

        .samperin-info-row {
            display: grid;
            grid-template-columns: 190px 15px minmax(0, 1fr);
            gap: 5px;
            min-height: 33px;
            align-items: center;
            border-bottom: 1px solid #e7edf4;
            color: #4d6688;
            font-size: 13px;
        }

        .samperin-info-row:last-child {
            border-bottom: 0;
        }

        .samperin-info-label {
            font-weight: 500;
        }

        .samperin-info-value {
            color: #4a6385;
            font-weight: 500;
            word-break: break-word;
        }

        /* =========================
           REQUESTS
        ========================= */

        .samperin-request-card {
            padding: 20px 15px;
        }

        .samperin-request-title {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 18px;
        }

        .samperin-request-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--samperin-blue);
            background: #eef6ff;
            font-size: 24px;
        }

        .samperin-request-heading {
            font-size: 15px;
            font-weight: 800;
            color: var(--samperin-navy);
            margin-top: 2px;
        }

        .samperin-request-description {
            margin-top: 5px;
            color: #536c8d;
            font-size: 12px;
            line-height: 1.5;
        }

        .samperin-request-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .samperin-request-item {
            width: 100%;
            min-height: 50px;
            padding: 10px 12px;
            border: 1px solid #dfe8f2;
            border-radius: 9px;
            background: linear-gradient(90deg, #fff, #fbfdff);
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--samperin-navy);
            transition: .18s ease;
        }

        .samperin-request-item:hover {
            border-color: #b9d6f8;
            background: #f7fbff;
            transform: translateX(2px);
        }

        .samperin-request-item-icon {
            color: #54708f;
            font-size: 20px;
        }

        .samperin-request-item-text {
            flex: 1;
            text-align: left;
            font-size: 12.5px;
            font-weight: 700;
            line-height: 1.35;
        }

        .samperin-request-item-arrow {
            color: #4c6688;
            font-size: 16px;
        }

        .samperin-no-request {
            padding: 28px 15px;
            border: 1px dashed #d7e2ed;
            border-radius: 9px;
            text-align: center;
            color: #7890aa;
            font-size: 12px;
        }

        .samperin-no-request i {
            display: block;
            font-size: 27px;
            margin-bottom: 8px;
        }

        /* =========================
           BERKAS
        ========================= */

        .samperin-document-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .samperin-document-title h2 {
            margin: 0;
            font-size: 21px;
            font-weight: 800;
        }

        .samperin-document-title p {
            margin: 4px 0 0;
            color: #617793;
            font-size: 13px;
        }

        .samperin-category-filter {
            display: flex;
            gap: 7px;
            overflow-x: auto;
            padding-bottom: 5px;
            scrollbar-width: none;
        }

        .samperin-category-filter::-webkit-scrollbar {
            display: none;
        }

        .samperin-category {
            white-space: nowrap;
            border: 1px solid #d9e4ef;
            background: #fff;
            color: #58708e;
            border-radius: 20px;
            padding: 7px 13px;
            font-size: 12px;
            font-weight: 600;
        }

        .samperin-category.active {
            background: var(--samperin-blue);
            color: #fff;
            border-color: var(--samperin-blue);
        }

        .samperin-document-section {
            background: #fff;
            border: 1px solid #dfe8f2;
            border-radius: 11px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .samperin-document-section-title {
            min-height: 49px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            background: #f8fbfe;
            border-bottom: 1px solid #e3eaf2;
            font-size: 14px;
            font-weight: 800;
            color: var(--samperin-navy);
        }

        .samperin-document-section-title i {
            color: var(--samperin-blue);
            margin-right: 9px;
        }

        .samperin-document-row {
            min-height: 64px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #edf1f5;
        }

        .samperin-document-row:last-child {
            border-bottom: 0;
        }

        .samperin-document-icon {
            width: 39px;
            height: 39px;
            border-radius: 8px;
            background: #eef5fc;
            color: #56718f;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 39px;
            font-size: 19px;
        }

        .samperin-document-name {
            flex: 1;
            color: var(--samperin-navy);
            font-weight: 600;
            font-size: 13px;
        }

        .samperin-document-date {
            color: #7388a1;
            font-size: 11px;
            margin-top: 3px;
        }

        .samperin-document-status {
            min-width: 145px;
            text-align: center;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .samperin-document-status.sudah {
            background: #e8f8f0;
            color: #07965a;
        }

        .samperin-document-status.belum {
            background: #fff3e4;
            color: #d67a13;
        }

        .samperin-document-view {
            border: 0;
            background: #edf5ff;
            color: var(--samperin-blue);
            border-radius: 6px;
            padding: 7px 11px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* =========================
           FOOTER
        ========================= */

        /* =========================================================
   FOOTER
========================================================= */

        .samperin-footer {
            position: relative;
            width: 100%;
            background: #fff;
            border-top: 1px solid #dfe8f2;
            margin-top: 30px;
            overflow: hidden;
        }

        .samperin-footer-accent {
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg,
                    #1677ff 0%,
                    #1677ff 72%,
                    #f28c28 88%,
                    #f28c28 100%);
        }

        .samperin-footer-inner {
            width: min(1450px, calc(100% - 56px));
            min-height: 112px;
            margin: 0 auto;
            padding: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }

        /* =========================================================
   BRAND
========================================================= */

        .samperin-footer-brand {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 0;
        }

        .samperin-footer-logo {
            width: 205px;
            height: auto;
            display: block;
        }

        .samperin-footer-tagline {
            margin-top: 6px;
            color: #7a8da8;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: .1px;
        }

        /* =========================================================
        UPLOAD MODAL BERKAS
========================================================= */
        /* =========================================================
   MODAL UPLOAD PEGAWAI
========================================================= */

        .samperin-upload-modal {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(16, 27, 77, .18);
        }

        .samperin-upload-modal .modal-header {
            padding: 20px 22px;
            border-bottom: 1px solid #edf1f7;
        }

        .samperin-upload-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .samperin-upload-icon {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #1677ff;
            color: #fff;

            font-size: 20px;
        }

        .samperin-upload-header h5 {
            margin: 0;

            color: #101b4d;

            font-size: 16px;
            font-weight: 700;
        }

        .samperin-upload-header p {
            margin: 3px 0 0;

            color: #7b879c;

            font-size: 12px;
        }

        .samperin-upload-modal .modal-body {
            padding: 22px;
        }

        .samperin-upload-request {
            padding: 14px 16px;

            border: 1px solid #b9d4ff;
            border-left: 4px solid #1677ff;

            border-radius: 10px;

            background: #f5f9ff;

            margin-bottom: 20px;
        }

        .samperin-upload-request-label {
            margin-bottom: 4px;

            color: #7b879c;

            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .samperin-upload-request-title {
            color: #10204a;

            font-size: 15px;
            font-weight: 700;
        }

        .samperin-upload-request-deadline {
            margin-top: 5px;

            color: #e05a47;

            font-size: 11px;
            font-style: italic;
            font-weight: 600;
        }

        .samperin-upload-field label {
            display: block;

            margin-bottom: 8px;

            color: #39445c;

            font-size: 13px;
            font-weight: 600;
        }

        .samperin-upload-field label span {
            color: #e05a47;
        }

        .samperin-file-box {
            position: relative;

            min-height: 100px;

            display: flex;
            align-items: center;

            padding: 16px;

            border: 1.5px dashed #b8cbea;
            border-radius: 12px;

            background: #f8fbff;

            cursor: pointer;
        }

        .samperin-file-box:hover {
            border-color: #1677ff;
            background: #f2f7ff;
        }

        .samperin-file-box input[type="file"] {
            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            opacity: 0;

            cursor: pointer;
        }

        .samperin-file-icon {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex: 0 0 40px;

            border-radius: 9px;

            background: #e7f0ff;
            color: #1677ff;

            font-size: 19px;
        }

        .samperin-file-text {
            margin-left: 12px;

            display: flex;
            flex-direction: column;

            min-width: 0;
        }

        .samperin-file-text strong {
            color: #24314d;

            font-size: 13px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .samperin-file-text small {
            margin-top: 3px;

            color: #8b96a9;

            font-size: 11px;
        }

        .samperin-upload-modal .modal-footer {
            padding: 15px 22px;

            border-top: 1px solid #edf1f7;
        }

        .samperin-upload-cancel,
        .samperin-upload-submit {
            min-height: 40px;

            padding: 0 16px;

            border-radius: 9px;

            font-size: 13px;
            font-weight: 600;
        }

        .samperin-upload-cancel {
            border: 1px solid #dce3ed;

            background: #fff;

            color: #657187;
        }

        .samperin-upload-submit {
            border: 0;

            background: #1677ff;

            color: #fff;
        }

        .samperin-upload-submit:hover {
            background: #0d5fd3;
        }

        /* =========================================================
   RIGHT
========================================================= */

        .samperin-footer-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 25px;
        }

        .samperin-footer-identity {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 25px;
            border-right: 1px solid #e2e9f1;
        }

        .samperin-berakhlak-logo {
            width: 155px;
            height: auto;
            display: block;
        }

        /* =========================================================
   COPYRIGHT
========================================================= */

        .samperin-footer-copy {
            min-width: 265px;
            text-align: right;
        }

        .samperin-footer-agency {
            color: #385579;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.5;
        }

        .samperin-footer-meta {
            margin-top: 4px;
            color: #8091a9;
            font-size: 11px;
            line-height: 1.5;
        }

        .samperin-footer-divider {
            margin: 0 5px;
            color: #b8c5d5;
        }

        .samperin-footer-created {
            margin-top: 3px;
            color: #8091a9;
            font-size: 11px;
        }

        .samperin-footer-created a {
            color: #1677ff;
            font-weight: 700;
            text-decoration: none;
        }

        .samperin-footer-created a:hover {
            text-decoration: underline;
        }


        /* =========================================================
   MOBILE
========================================================= */

        @media (max-width: 767px) {

            .samperin-footer {
                margin-top: 20px;
            }

            .samperin-footer-accent {
                height: 2px;
            }

            .samperin-footer-inner {
                width: calc(100% - 24px);
                min-height: 0;
                padding: 18px 0;
                flex-direction: column;
                align-items: center;
                gap: 16px;
            }

            .samperin-footer-brand {
                align-items: center;
                text-align: center;
            }

            .samperin-footer-logo {
                width: 175px;
            }

            .samperin-footer-tagline {
                font-size: 9px;
                margin-top: 5px;
            }

            .samperin-footer-right {
                width: 100%;
                flex-direction: column;
                gap: 12px;
            }

            .samperin-footer-identity {
                padding-right: 0;
                padding-bottom: 12px;
                border-right: 0;
                border-bottom: 1px solid #e2e9f1;
            }

            .samperin-berakhlak-logo {
                width: 135px;
            }

            .samperin-footer-copy {
                min-width: 0;
                width: 100%;
                text-align: center;
            }

            .samperin-footer-agency {
                font-size: 10.5px;
            }

            .samperin-footer-meta,
            .samperin-footer-created {
                font-size: 10px;
            }
        }

        /* =========================================================
   HEADER ROLE
========================================================= */

        .samperin-header-role {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            height: 38px;
            padding: 0 13px;
            margin-left: 14px;

            border: 1px solid #dce5ef;
            border-radius: 10px;

            background: #fff;
            color: #182238;

            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;

            cursor: pointer;
            white-space: nowrap;

            transition: all .2s ease;
        }

        .samperin-header-role:hover {
            border-color: var(--samperin-blue);
            background: var(--samperin-blue-soft);
        }

        .samperin-header-role i:first-child {
            color: var(--samperin-blue);
            font-size: 17px;
        }

        .samperin-header-role i:last-child {
            color: #8090a5;
            font-size: 10px;
        }

        .samperin-footer-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--samperin-navy);
        }

        .samperin-footer-text {
            font-size: 11px;
            color: #506987;
            line-height: 1.45;
        }

        .samperin-footer-right {
            display: flex;
            align-items: center;
            gap: 55px;
        }

        .samperin-berakhlak {
            font-size: 21px;
            font-weight: 900;
            color: #df1616;
        }

        .samperin-footer-copy {
            color: #536b87;
            font-size: 11px;
            text-align: right;
            line-height: 1.6;
        }

        /* =========================
   ROLE MODAL
========================= */

        .samperin-role-modal-dialog {
            max-width: 430px;
            padding: 12px;
        }

        .samperin-role-modal {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 18px 50px rgba(20, 45, 80, .18);
        }

        .samperin-role-modal-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #e7edf4;
            background: #fff;
        }

        .samperin-role-modal-title {
            color: var(--samperin-navy);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .samperin-role-modal-subtitle {
            margin-top: 5px;
            color: #71849e;
            font-size: 12px;
            line-height: 1.4;
        }

        .samperin-role-modal-body {
            padding: 15px;
            background: #fbfdff;
        }

        .samperin-role-form {
            margin: 0 0 9px;
        }

        .samperin-role-form:last-child {
            margin-bottom: 0;
        }

        .samperin-role-option {
            width: 100%;
            min-height: 68px;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 10px 12px;

            border: 1px solid #dfe8f2;
            border-radius: 12px;

            background: #fff;
            color: var(--samperin-navy);

            text-align: left;

            transition:
                border-color .18s ease,
                background .18s ease,
                box-shadow .18s ease,
                transform .18s ease;
        }

        .samperin-role-option:not(:disabled) {
            cursor: pointer;
        }

        .samperin-role-option:not(:disabled):hover {
            border-color: #a9cef7;
            background: #f5faff;
            box-shadow: 0 4px 12px rgba(22, 119, 255, .07);
            transform: translateY(-1px);
        }

        .samperin-role-option.active {
            border-color: #9cc8f8;
            background: #edf6ff;
            box-shadow: none;
        }

        .samperin-role-option:disabled {
            opacity: 1;
            cursor: default;
        }

        .samperin-role-option-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: #edf5ff;
            color: var(--samperin-blue);

            font-size: 19px;
        }

        .samperin-role-option.active .samperin-role-option-icon {
            background: #dceeff;
            color: var(--samperin-blue);
        }

        .samperin-role-option-content {
            flex: 1;
            min-width: 0;
        }

        .samperin-role-option-name {
            color: var(--samperin-navy);
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
        }

        .samperin-role-option-status {
            display: flex;
            align-items: center;
            gap: 4px;

            margin-top: 4px;

            color: var(--samperin-blue);
            font-size: 11px;
            font-weight: 600;
        }

        .samperin-role-option-status i {
            font-size: 11px;
        }

        .samperin-role-option-check {
            color: var(--samperin-blue);
            font-size: 18px;
        }

        .samperin-role-option-arrow {
            color: #9aabc0;
            font-size: 15px;
        }

        .samperin-role-empty {
            padding: 28px 15px;
            text-align: center;
        }

        .samperin-role-empty-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #edf3f8;
            color: #8496aa;

            font-size: 22px;
        }

        .samperin-role-empty-title {
            color: var(--samperin-navy);
            font-size: 14px;
            font-weight: 700;
        }

        .samperin-role-empty-text {
            margin-top: 4px;
            color: #7b8da3;
            font-size: 12px;
        }

        .samperin-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;

            margin: 0 0 18px;

            padding: 13px 16px;

            border-radius: 10px;

            font-size: 13px;
        }

        .samperin-alert>i {
            margin-top: 2px;
            font-size: 17px;
        }

        .samperin-alert ul {
            padding-left: 18px;
        }

        .samperin-alert li {
            margin-bottom: 2px;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 1200px) {
            .samperin-brand {
                min-width: 310px;
            }

            .samperin-account {
                min-width: 260px;
            }

            .samperin-profile-hero-content {
                grid-template-columns: 210px minmax(0, 1fr);
            }

            .samperin-quote {
                display: none;
            }

            .samperin-content-grid {
                grid-template-columns: 1fr;
            }

            .samperin-request-card {
                order: -1;
            }
        }

        @media (max-width: 900px) {
            .samperin-header {
                height: auto;
            }

            .samperin-header-inner {
                min-height: 76px;
                flex-wrap: wrap;
                padding: 8px 0;
            }

            .samperin-brand {
                min-width: 0;
                flex: 1;
            }

            .samperin-brand-logo {
                width: 146px;
                height: 51px;
            }

            .samperin-header-role {
                height: 36px;
                padding: 0 10px;
                margin-left: 8px;
                font-size: 12px;
            }

            .samperin-brand-name {
                font-size: 17px;
            }

            .samperin-brand-subtitle,
            .samperin-brand-office {
                font-size: 10px;
            }

            .samperin-account {
                min-width: auto;
            }

            .samperin-account .samperin-user-info,
            .samperin-user-chevron {
                display: none;
            }

            .samperin-header-nav {
                order: 3;
                flex: 0 0 100%;
                height: 52px;
                border-top: 1px solid #edf1f5;
            }

            .samperin-nav-item {
                height: 52px;
                min-width: 120px;
                font-size: 13px;
            }

            .samperin-nav-item i {
                font-size: 18px;
            }

            .samperin-main {
                padding-top: 20px;
            }

            .samperin-profile-hero-content {
                grid-template-columns: 175px minmax(0, 1fr);
                gap: 18px;
            }

            .samperin-profile-photo,
            .samperin-profile-placeholder,
            .samperin-photo-button {
                width: 145px;
            }

            .samperin-profile-photo,
            .samperin-profile-placeholder {
                height: 166px;
            }

            .samperin-profile-name {
                font-size: 22px;
            }

            .samperin-profile-position {
                font-size: 14px;
            }

            .samperin-profile-office {
                font-size: 13px;
            }

            .samperin-info-grid {
                grid-template-columns: 1fr;
            }

            .samperin-footer-inner,
            .samperin-footer-right {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 600px) {

            .samperin-container,
            .samperin-header-inner,
            .samperin-footer-inner {
                width: min(100% - 24px, 1430px);
            }

            .samperin-page-title h1 {
                font-size: 24px;
            }

            .samperin-page-title p {
                font-size: 13px;
            }

            .samperin-profile-hero {
                padding: 13px;
            }

            .samperin-profile-hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .samperin-profile-photo-wrap {
                align-items: center;
            }

            .samperin-profile-badges {
                justify-content: center;
            }

            .samperin-profile-name {
                font-size: 21px;
            }

            .samperin-info-row {
                grid-template-columns: 120px 10px minmax(0, 1fr);
                font-size: 12px;
            }

            .samperin-tab-content {
                padding: 12px 10px 18px;
            }

            .samperin-request-card {
                padding: 15px 11px;
            }

            .samperin-document-header {
                display: block;
            }

            .samperin-category-filter {
                margin-top: 12px;
            }

            .samperin-document-row {
                flex-wrap: wrap;
            }

            .samperin-document-name {
                min-width: calc(100% - 55px);
            }

            .samperin-document-status,
            .samperin-document-view {
                margin-left: 54px;
            }

            .samperin-footer-right {
                gap: 20px;
            }

            .samperin-footer-copy {
                text-align: left;
            }

            .samperin-role-modal-dialog {
                margin: 10px auto;
                padding: 8px;
            }

            .samperin-role-modal-header {
                padding: 17px 16px 14px;
            }

            .samperin-role-modal-body {
                padding: 12px;
            }

            .samperin-role-option {
                min-height: 62px;
            }

            .samperin-role-option-icon {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
                font-size: 17px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="samperin-page">

        @include('pegawai.partials.header')
        @include('pegawai.partials.role-modal')

        <main class="samperin-main">
            {{-- ALERT SUCCESS --}}
            @if (session('success'))
                <div class="alert alert-success samperin-alert" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- ALERT ERROR --}}
            @if (session('error'))
                <div class="alert alert-danger samperin-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- VALIDATION ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger samperin-alert" role="alert">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <div>
                        <strong>Terdapat kesalahan pada data.</strong>

                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            @endif
            @yield('content')
        </main>

        <footer class="samperin-footer">

            <div class="samperin-footer-accent"></div>

            <div class="samperin-footer-inner">

                {{-- BRAND --}}
                <div class="samperin-footer-brand">

                    <img src="{{ asset('assets/images/logo-samperin-full.png') }}" class="samperin-footer-logo"
                        alt="SAMPERIN" onerror="this.style.display='none'">

                </div>


                {{-- RIGHT --}}
                <div class="samperin-footer-right">

                    <div class="samperin-footer-identity">

                        <img src="{{ asset('assets/images/asn-berakhlak.png') }}" class="samperin-berakhlak-logo"
                            alt="ASN BerAKHLAK">

                    </div>


                    <div class="samperin-footer-copy">

                        <div class="samperin-footer-agency">
                            © {{ date('Y') }} Dinas Kebudayaan Provinsi Bali
                        </div>

                        <div class="samperin-footer-meta">

                            <span>
                                {{ config('app.version') }}
                            </span>

                            <span class="samperin-footer-divider">•</span>

                            <span>
                                Build {{ config('app.build') }}
                            </span>

                        </div>

                        <div class="samperin-footer-created">
                            Created by
                            <a href="https://arinl.site" target="_blank" rel="noopener">
                                ARIN
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>

</html>
