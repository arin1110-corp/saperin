<style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        min-height: 100%;
    }

    body {
        font-family:
            Inter,
            ui-sans-serif,
            system-ui,
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            sans-serif;

        background: #f3f1ef;
    }


    /* =========================================================
       BUILDING
    ========================================================== */

    .building-panel {
        position: relative;

        background-image:

            linear-gradient(180deg,
                rgba(5, 3, 2, .84) 0%,
                rgba(17, 9, 4, .82) 25%,
                rgba(28, 14, 6, .84) 50%,
                rgba(12, 6, 3, .94) 75%,
                rgba(3, 2, 1, .98) 100%),

            linear-gradient(90deg,
                rgba(0, 0, 0, .35) 0%,
                rgba(0, 0, 0, .08) 55%,
                rgba(0, 0, 0, .45) 100%),

            url('{{ asset('assets/images/gedung-disbud.jpg') }}');

        background-size: cover;

        background-position: center;

        background-repeat: no-repeat;
    }


    .building-panel::after {
        content: '';

        position: absolute;

        inset: 0;

        pointer-events: none;

        background:
            linear-gradient(180deg,
                transparent 0%,
                rgba(0, 0, 0, .12) 50%,
                rgba(0, 0, 0, .35) 100%);
    }


    /* =========================================================
       PATTERN
    ========================================================== */

    .bali-pattern {
        position: absolute;

        top: 0;
        right: 0;

        width: 300px;
        height: 470px;

        opacity: .10;

        pointer-events: none;

        background-image:
            radial-gradient(circle,
                rgba(244, 191, 98, .9) 0,
                rgba(244, 191, 98, .9) 1px,
                transparent 1.5px);

        background-size: 14px 14px;

        mask-image:
            linear-gradient(to bottom,
                black,
                transparent);

        -webkit-mask-image:
            linear-gradient(to bottom,
                black,
                transparent);
    }


    /* =========================================================
       CONTENT
    ========================================================== */

    .left-content {
        position: relative;

        z-index: 5;
    }


    /* =========================================================
       BRAND
    ========================================================== */

    .samperin-brand {
        font-size:
            clamp(40px,
                4vw,
                58px);

        font-weight: 900;

        line-height: .95;

        letter-spacing: -.075em;
    }

    .samperin-normal {
        color: #fff;
    }

    .samperin-in {
        color: #b85b17;
    }


    /* =========================================================
       GOLD LINE
    ========================================================== */

    .gold-line {
        width: 64px;

        height: 3px;

        margin-top: 24px;

        border-radius: 999px;

        background: #f4bd5d;
    }


    /* =========================================================
       FEATURES
    ========================================================== */

    .feature-box {
        background:
            rgba(8,
                5,
                3,
                .72);

        border:
            1px solid rgba(255,
                255,
                255,
                .24);

        backdrop-filter: blur(6px);

        -webkit-backdrop-filter: blur(6px);
    }


    .feature-item {
        border-bottom:
            1px solid rgba(255,
                255,
                255,
                .17);
    }


    .feature-item:last-child {
        border-bottom: none;
    }


    /* =========================================================
       INPUT
    ========================================================== */

    .login-input {
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease;
    }


    .login-input:focus {
        outline: none;

        border-color: #a85514;

        background: #fff;

        box-shadow:
            0 0 0 4px rgba(168,
                85,
                20,
                .09);
    }


    /* =========================================================
       BUTTON
    ========================================================== */

    .login-button {
        transition:
            background-color .2s ease,
            transform .15s ease,
            box-shadow .2s ease;
    }


    .login-button:hover {
        background: #98480d;

        box-shadow:
            0 12px 28px rgba(152,
                72,
                13,
                .24);
    }


    .login-button:active {
        transform: translateY(1px);
    }


    /* =========================================================
       SECURITY
    ========================================================== */

    .security-box {
        border:
            1px solid #ece8e3;

        background: #faf9f7;
    }


    /* =========================================================
       DESKTOP
    ========================================================== */

    @media (min-width: 1024px) {

        body {
            min-height: 100vh;

            overflow-y: auto;
            overflow-x: hidden;
        }

        .login-wrapper {
            min-height: calc(100vh - 24px);

            max-height: none;
        }

    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 1023px) {

        body {
            overflow-y: auto;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        .building-panel {
            min-height: 620px;
        }

    }


    @media (max-width: 640px) {

        .building-panel {
            min-height: 650px;
        }

        .samperin-brand {
            font-size: 42px;
        }

    }
</style>
