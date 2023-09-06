<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">
               <div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
                  <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
                     <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
                        <i class="ki-outline ki-abstract-14 fs-2"></i>
                     </div>
                  </div>
                  <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-1 me-lg-13">
                     <a href="/">
                     <img alt="Logo" src="<?= $logo ?>" class="h-20px h-lg-60px theme-light-show" />
                     <img alt="Logo" src="<?= $logo ?>" class="h-20px h-lg-60px theme-dark-show" />
                     </a>
                  </div>
                  <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                    <?php include(__DIR__ . '/sidebar.php') ?>
                     <div class="app-navbar flex-shrink-0">
                        <div class="app-navbar-item" id="kt_header_user_menu_toggle">
                           <div class="d-flex align-items-center border border-dashed border-gray-300 rounded p-2" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                              <div class="cursor-pointer symbol me-3 symbol-35px symbol-lg-45px">
                                 <img class="" src="<?= $sessionManager->getUserInfo('avatar') ?>" alt="user" />
                              </div>
                              <div class="me-4">
                                 <a href="../../demo43/dist/pages/user-profile/projects.html" class="text-dark text-hover-primary fs-6 fw-bold"><?= $kosma_encryption->decrypt($sessionManager->getUserInfo('first_name'), $kosma_encryption_key) ?> <?= $kosma_encryption->decrypt($sessionManager->getUserInfo('last_name'), $kosma_encryption_key) ?></a>
                                 <a href="../../demo43/dist/pages/user-profile/overview.html" class="text-gray-400 text-hover-primary fs-7 fw-bold d-block">@<?= $kosma_encryption->decrypt($sessionManager->getUserInfo('username'), $kosma_encryption_key) ?> </a>
                              </div>
                              <i class="ki-outline ki-down fs-2 text-gray-500 pt-1"></i>
                           </div>
                           <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                              <div class="menu-item px-3">
                                 <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-50px me-5">
                                       <img alt="Logo" src="<?= $sessionManager->getUserInfo('avatar') ?>" />
                                    </div>
                                    <div class="d-flex flex-column">
                                       <div class="fw-bold d-flex align-items-center fs-5"><?= $kosma_encryption->decrypt($sessionManager->getUserInfo('first_name'), $kosma_encryption_key) ?> <?= $kosma_encryption->decrypt($sessionManager->getUserInfo('last_name'), $kosma_encryption_key) ?>
                                          <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"><?= $sessionManager->getUserInfo('role') ?></span>
                                       </div>
                                       <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">@<?= $kosma_encryption->decrypt($sessionManager->getUserInfo('username'), $kosma_encryption_key) ?></a>
                                    </div>
                                 </div>
                              </div>                              
                              <div class="separator my-2"></div>
                              <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                 <a href="#" class="menu-link px-5">
                                 <span class="menu-title position-relative">Mode
                                 <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                 <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                 <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                 </span></span>
                                 </a>
                                 <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                    <div class="menu-item px-3 my-0">
                                       <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                       <span class="menu-icon" data-kt-element="icon">
                                       <i class="ki-outline ki-night-day fs-2"></i>
                                       </span>
                                       <span class="menu-title">Light</span>
                                       </a>
                                    </div>
                                    <div class="menu-item px-3 my-0">
                                       <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                       <span class="menu-icon" data-kt-element="icon">
                                       <i class="ki-outline ki-moon fs-2"></i>
                                       </span>
                                       <span class="menu-title">Dark</span>
                                       </a>
                                    </div>
                                    <div class="menu-item px-3 my-0">
                                       <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                       <span class="menu-icon" data-kt-element="icon">
                                       <i class="ki-outline ki-screen fs-2"></i>
                                       </span>
                                       <span class="menu-title">System</span>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                              <!--<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                 <a href="#" class="menu-link px-5">
                                 <span class="menu-title position-relative">Language
                                 <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English
                                 <img class="w-15px h-15px rounded-1 ms-2" src="/assets/media/flags/united-states.svg" alt="" /></span></span>
                                 </a>
                                 <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                    <div class="menu-item px-3">
                                       <a href="../../demo43/dist/account/settings.html" class="menu-link d-flex px-5 active">
                                       <span class="symbol symbol-20px me-4">
                                       <img class="rounded-1" src="/assets/media/flags/united-states.svg" alt="" />
                                       </span>English</a>
                                    </div>
                                    <div class="menu-item px-3">
                                       <a href="../../demo43/dist/account/settings.html" class="menu-link d-flex px-5">
                                       <span class="symbol symbol-20px me-4">
                                       <img class="rounded-1" src="/assets/media/flags/spain.svg" alt="" />
                                       </span>Spanish</a>
                                    </div>
                                    <div class="menu-item px-3">
                                       <a href="../../demo43/dist/account/settings.html" class="menu-link d-flex px-5">
                                       <span class="symbol symbol-20px me-4">
                                       <img class="rounded-1" src="/assets/media/flags/germany.svg" alt="" />
                                       </span>German</a>
                                    </div>
                                    <div class="menu-item px-3">
                                       <a href="../../demo43/dist/account/settings.html" class="menu-link d-flex px-5">
                                       <span class="symbol symbol-20px me-4">
                                       <img class="rounded-1" src="/assets/media/flags/japan.svg" alt="" />
                                       </span>Japanese</a>
                                    </div>
                                    <div class="menu-item px-3">
                                       <a href="../../demo43/dist/account/settings.html" class="menu-link d-flex px-5">
                                       <span class="symbol symbol-20px me-4">
                                       <img class="rounded-1" src="/assets/media/flags/france.svg" alt="" />
                                       </span>French</a>
                                    </div>
                                 </div>
                              </div>-->
                              <div class="menu-item px-5 my-1">
                                 <a href="/user/settings" class="menu-link px-5">Account Settings</a>
                              </div>
                              <div class="menu-item px-5">
                                 <a href="/auth/logout" class="menu-link px-5">Sign Out</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>