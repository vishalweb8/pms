<div class="welcome_star_wrapper">
    <div class="user-container">
        <div class="user">
            <div class="box">
                <div class="title">
                    <span class="block"></span>
                    <h1 class="greet">Welcome</h1>
                </div>
                <div class="role">
                    <div class="block"></div>
                    <h3 class="name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="welcome_star">
        <div class="loading-init">
            <div class="loading-init__header"></div>
            <div class="loading-init__status"></div>
        </div>
        <div class="stage-container remove">
            <div class="canvas-container">
                <canvas id="trails-canvas"></canvas>
                <canvas id="main-canvas"></canvas>
            </div>
            <div class="controls">
                <div class="btn pause-btn">
                    <svg fill="white" width="24" height="24">
                        <use href="#icon-pause" xlink:href="#icon-pause"></use>
                    </svg>
                </div>
                <div class="btn sound-btn">
                    <svg fill="white" width="24" height="24">
                        <use href="#icon-sound-off" xlink:href="#icon-sound-off"></use>
                    </svg>
                </div>
                <div class="btn settings-btn">
                    <svg fill="white" width="24" height="24">
                        <use href="#icon-settings" xlink:href="#icon-settings"></use>
                    </svg>
                </div>
            </div>
            <div class="menu d-none">
                <div class="menu__inner-wrap">
                    <div class="btn btn--bright close-menu-btn">
                        <svg fill="white" width="24" height="24">
                            <use href="#icon-close" xlink:href="#icon-close"></use>
                        </svg>
                    </div>
                    <div class="menu__header">Settings</div>
                    <div class="menu__subheader">For more info, click any label.</div>
                    <form>
                        <div class="form-option form-option--select">
                            <label class="shell-type-label">Shell Type</label>
                            <select class="shell-type"></select>
                        </div>
                        <div class="form-option form-option--select">
                            <label class="shell-size-label">Shell Size</label>
                            <select class="shell-size"></select>
                        </div>
                        <div class="form-option form-option--select">
                            <label class="quality-ui-label">Quality</label>
                            <select class="quality-ui"></select>
                        </div>
                        <div class="form-option form-option--select">
                            <label class="sky-lighting-label">Sky Lighting</label>
                            <select class="sky-lighting"></select>
                        </div>
                        <div class="form-option form-option--select">
                            <label class="scaleFactor-label">Scale</label>
                            <select class="scaleFactor"></select>
                        </div>
                        <div class="form-option form-option--checkbox">
                            <label class="auto-launch-label">Auto Fire</label>
                            <input class="auto-launch" type="checkbox" />
                        </div>
                        <div class="form-option form-option--checkbox form-option--finale-mode">
                            <label class="finale-mode-label">Finale Mode</label>
                            <input class="finale-mode" type="checkbox" />
                        </div>
                        <div class="form-option form-option--checkbox">
                            <label class="hide-controls-label">Hide Controls</label>
                            <input class="hide-controls" type="checkbox" />
                        </div>
                        <div class="form-option form-option--checkbox form-option--fullscreen">
                            <label class="fullscreen-label">Fullscreen</label>
                            <input class="fullscreen" type="checkbox" />
                        </div>
                        <div class="form-option form-option--checkbox">
                            <label class="long-exposure-label">Open Shutter</label>
                            <input class="long-exposure" type="checkbox" />
                        </div>
                    </form>
                    <div class="credits">
                        Passionately built Sayn Achhava.
                    </div>
                </div>
            </div>
        </div>
        <div class="help-modal d-none">
            <div class="help-modal__overlay"></div>
            <div class="help-modal__dialog">
                <div class="help-modal__header"></div>
                <div class="help-modal__body"></div>
                <button type="button" class="help-modal__close-btn d-none"></button>
            </div>
        </div>
    </div>
</div>
