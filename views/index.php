<?php
include(__DIR__ . '/requirements/page.php');

?>
<!DOCTYPE html>
<!--
   Author: Keenthemes
   Product Name: Metronic
   Product Version: 8.1.8
   Purchase: https://1.envato.market/EA4JP
   Website: http://www.keenthemes.com
   Contact: support@keenthemes.com
   Follow: www.twitter.com/keenthemes
   Dribbble: www.dribbble.com/keenthemes
   Like: www.facebook.com/keenthemes
   License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
   -->
<html lang="en">
   <head>
      <base href="/"/>
      <title>
         <?= $settingsManager->getSetting('name') ?> - Dashboard
      </title>
      <?php include(__DIR__ . '/requirements/head.php'); ?>
      <link rel="icon" type="image/x-icon" href="<?= $logo ?>" />
   </head>
   <body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
      <script>
         var defaultThemeMode = "light";
         var themeMode;
         if (document.documentElement) {
             if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                 themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
             } else {
                 if (localStorage.getItem("data-bs-theme") !== null) { themeMode = localStorage.getItem("data-bs-theme"); }
                 else { themeMode = defaultThemeMode; }
             }
             if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; }
             document.documentElement.setAttribute("data-bs-theme", themeMode);
         }
      </script>		
      <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
         <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
         <?php include(__DIR__ . '/components/navbar.php') ?>
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
               <div class="app-container container-xxl d-flex">
                  <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                     <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content">
                           <div class="row g-5 g-xxl-10">
                              <div class="col-xxl-4 mb-xxl-10">
                                 <div class="card card-reset mb-5 mb-xl-10">
                                    <div class="card-body p-0">
                                       <div class="row g-5 g-lg-9">
                                          <div class="col-6">
                                             <div class="card card-shadow">
                                                <div class="card-body p-0">
                                                   <a href='../../demo43/dist/apps/subscriptions/list.html' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                                      <i class="ki-outline ki-plus-square fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                                      <div class="fw-bold fs-5 pt-4">Add New</div>
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-6">
                                             <div class="card card-shadow">
                                                <div class="card-body p-0">
                                                   <a href='../../demo43/dist/apps/ecommerce/catalog/products.html' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                                      <i class="ki-outline ki-element-11 fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                                      <div class="fw-bold fs-5 pt-4">eCommerce</div>
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-6">
                                             <div class="card card-shadow">
                                                <div class="card-body p-0">
                                                   <a href='../../demo43/dist/pages/contact.html' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                                      <i class="ki-outline ki-message-edit fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                                      <div class="fw-bold fs-5 pt-4">Contacts</div>
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-6">
                                             <div class="card card-shadow">
                                                <div class="card-body p-0">
                                                   <a href='../../demo43/dist/apps/file-manager/folders.html' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                                      <i class="ki-outline ki-rocket fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                                      <div class="fw-bold fs-5 pt-4">File Manager</div>
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-6">
                                             <div class="card card-shadow">
                                                <div class="card-body p-0">
                                                   <a href='../../demo43/dist/apps/subscriptions/list.html' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                                      <i class="ki-outline ki-chart-pie-3 fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                                      <div class="fw-bold fs-5 pt-4">Subscriptions</div>
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-6">
                                             <div class="card card-shadow">
                                                <div class="card-body p-0">
                                                   <a href='../../demo43/dist/apps/support-center/overview.html' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                                      <i class="ki-outline ki-rescue fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                                      <div class="fw-bold fs-5 pt-4">Help Center</div>
                                                   </a>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card border-0">
                                    <div class="card-body py-12">
                                       <button class="btn btn-light-warning fs-3 fw-bolder w-100 py-5 mb-13" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Campaign Glossary</button>
                                       <div class="d-flex border border-primary border-dashed rounded p-5 bg-light-primary mb-6">
                                          <span class="me-5">
                                          <i class="ki-outline ki-information fs-3x text-primary"></i>
                                          </span>
                                          <div class="me-2">
                                             <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bolder">Important Note!</a>
                                             <span class="text-gray-700 fw-semibold d-block fs-5">Please add tracking code to your website to optimize your campaigns</span>
                                          </div>
                                       </div>
                                       <button class="btn btn-primary fs-3 fw-bolder w-100 py-5" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Add Conversion Tracking</button>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-xxl-8 mb-5 mb-xl-10">
                                 <div class="card border-0 mb-5 mb-xl-11" data-bs-theme="light" style="background-color: #844AFF">
                                    <div class="card-body py-0">
                                       <div class="row align-items-center lh-1 h-100">
                                          <div class="col-7 ps-xl-10 pe-5">
                                             <div class="fs-2qx fw-bold text-white mb-6">Upgrade Your Plan</div>
                                             <span class="fw-semibold text-white fs-6 mb-10 d-block opacity-75">Flat cartoony and illustrations with vivid unblended purple hair lady</span>
                                             <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-9">
                                                <div class="d-flex align-items-center me-5 me-xl-13">
                                                   <div class="symbol symbol-30px symbol-circle me-3">
                                                      <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                                      <i class="ki-outline ki-abstract-41 fs-5 text-white"></i>
                                                      </span>
                                                   </div>
                                                   <div class="text-white">
                                                      <span class="fw-semibold d-block fs-8 opacity-75 mb-2">Projects</span>
                                                      <span class="fw-bold fs-7">Up to 500</span>
                                                   </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                   <div class="symbol symbol-30px symbol-circle me-3">
                                                      <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                                      <i class="ki-outline ki-abstract-26 fs-5 text-white"></i>
                                                      </span>
                                                   </div>
                                                   <div class="text-white">
                                                      <span class="fw-semibold opacity-75 d-block fs-8 mb-2">Tasks</span>
                                                      <span class="fw-bold fs-7">Unlimited</span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="d-flex d-grid gap-2">
                                                <a href="#" class="btn btn-success me-lg-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade</a>
                                                <a href="#" class="btn text-white" style="background: rgba(255, 255, 255, 0.2)" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Help</a>
                                             </div>
                                          </div>
                                          <div class="col-5 pt-5 pt-lg-15">
                                             <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end bgi-position-y-bottom h-325px" style="background-image:url('/assets/media/svg/illustrations/easy/8.svg"></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card card-flush border-0">
                                    <div class="card-header pt-7">
                                       <h3 class="card-title align-items-start flex-column">
                                          <span class="card-label fs-3 fw-bold text-gray-800">Campaigns</span>
                                          <span class="text-gray-400 mt-1 fw-semibold fs-6">Select a campaign & date range to view data</span>
                                       </h3>
                                       <div class="card-toolbar">
                                          <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" class="btn btn-sm btn-light d-flex align-items-center px-4">
                                             <div class="text-gray-600 fw-bold">Loading date range...</div>
                                             <i class="ki-outline ki-calendar-8 fs-1 ms-2 me-0"></i>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card-body pt-6">
                                       <div class="table-responsive">
                                          <table class="table table-row-dashed align-middle gs-0 gy-6 my-0">
                                             <thead>
                                                <tr class="fs-7 fw-bold text-gray-400">
                                                   <th class="p-0 pb-3 w-150px text-start">STATUS</th>
                                                   <th class="p-0 pb-3 min-w-100px text-start">NAME</th>
                                                   <th class="p-0 pb-3 min-w-100px text-center">BUDGET</th>
                                                   <th class="p-0 pb-3 w-250px text-start">OPTIMIZATION SCORE</th>
                                                   <th class="p-0 pb-3 w-50px text-end">ACTION</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <tr>
                                                   <td>
                                                      <span class="badge py-3 px-4 fs-7 badge-light-success">Live Now</span>
                                                   </td>
                                                   <td class="ps-0 text-start">
                                                      <span class="text-gray-800 fw-bold fs-6 d-block">Marni Schlanger</span>
                                                      <span class="text-gray-400 fw-semibold fs-7">20 Jul 2021</span>
                                                   </td>
                                                   <td class="text-center">
                                                      <span class="text-gray-800 fw-bold fs-6">$15</span>
                                                      <span class="text-gray-400 fw-bold fs-7 d-block">Daily</span>
                                                   </td>
                                                   <td class="ps-0 pe-20">
                                                      <div class="progress bg-light-primary rounded">
                                                         <div class="progress-bar bg-primary rounded m-0" role="progressbar" style="height: 12px;width: 120px" aria-valuenow="120" aria-valuemin="0" aria-valuemax="120px"></div>
                                                      </div>
                                                   </td>
                                                   <td class="text-center">
                                                      <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                      <i class="ki-outline ki-black-right fs-5 text-gray-700"></i>
                                                      </a>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td>
                                                      <span class="badge py-3 px-4 fs-7 badge-light-primary">Reviewing</span>
                                                   </td>
                                                   <td class="ps-0 text-start">
                                                      <span class="text-gray-800 fw-bold fs-6 d-block">Addison Smart</span>
                                                      <span class="text-gray-400 fw-semibold fs-7">19 Jul 2021</span>
                                                   </td>
                                                   <td class="text-center">
                                                      <span class="text-gray-800 fw-bold fs-6">$10</span>
                                                      <span class="text-gray-400 fw-bold fs-7 d-block">Daily</span>
                                                   </td>
                                                   <td class="ps-0 pe-20">
                                                      <div class="progress bg-light-primary rounded">
                                                         <div class="progress-bar bg-primary rounded m-0" role="progressbar" style="height: 12px;width: 10px" aria-valuenow="10" aria-valuemin="0" aria-valuemax="10px"></div>
                                                      </div>
                                                   </td>
                                                   <td class="text-center">
                                                      <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                      <i class="ki-outline ki-black-right fs-5 text-gray-700"></i>
                                                      </a>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td>
                                                      <span class="badge py-3 px-4 fs-7 badge-light-warning">Paused</span>
                                                   </td>
                                                   <td class="ps-0 text-start">
                                                      <span class="text-gray-800 fw-bold fs-6 d-block">Paul Melone</span>
                                                      <span class="text-gray-400 fw-semibold fs-7">21 Jul 2021</span>
                                                   </td>
                                                   <td class="text-center">
                                                      <span class="text-gray-800 fw-bold fs-6">$3</span>
                                                      <span class="text-gray-400 fw-bold fs-7 d-block">Daily</span>
                                                   </td>
                                                   <td class="ps-0 pe-20">
                                                      <div class="progress bg-light-primary rounded">
                                                         <div class="progress-bar bg-primary rounded m-0" role="progressbar" style="height: 12px;width: 60px" aria-valuenow="60" aria-valuemin="0" aria-valuemax="60px"></div>
                                                      </div>
                                                   </td>
                                                   <td class="text-center">
                                                      <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                      <i class="ki-outline ki-black-right fs-5 text-gray-700"></i>
                                                      </a>
                                                   </td>
                                                </tr>
                                                <tr>
                                                   <td>
                                                      <span class="badge py-3 px-4 fs-7 badge-light-success">Live Now</span>
                                                   </td>
                                                   <td class="ps-0 text-start">
                                                      <span class="text-gray-800 fw-bold fs-6 d-block">Marni Schlanger</span>
                                                      <span class="text-gray-400 fw-semibold fs-7">23 Jul 2021</span>
                                                   </td>
                                                   <td class="text-center">
                                                      <span class="text-gray-800 fw-bold fs-6">$23</span>
                                                      <span class="text-gray-400 fw-bold fs-7 d-block">Daily</span>
                                                   </td>
                                                   <td class="ps-0 pe-20">
                                                      <div class="progress bg-light-primary rounded">
                                                         <div class="progress-bar bg-primary rounded m-0" role="progressbar" style="height: 12px;width: 160px" aria-valuenow="160" aria-valuemin="0" aria-valuemax="160px"></div>
                                                      </div>
                                                   </td>
                                                   <td class="text-center">
                                                      <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                      <i class="ki-outline ki-black-right fs-5 text-gray-700"></i>
                                                      </a>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                       <div class="separator separator-dashed border-gray-200 mb-n4"></div>
                                       <div class="d-flex flex-stack flex-wrap pt-10">
                                          <div class="fs-6 fw-semibold text-gray-700">Showing 1 to 10 of 50 entries</div>
                                          <ul class="pagination">
                                             <li class="page-item previous">
                                                <a href="#" class="page-link">
                                                <i class="previous"></i>
                                                </a>
                                             </li>
                                             <li class="page-item active">
                                                <a href="#" class="page-link">1</a>
                                             </li>
                                             <li class="page-item">
                                                <a href="#" class="page-link">2</a>
                                             </li>
                                             <li class="page-item">
                                                <a href="#" class="page-link">3</a>
                                             </li>
                                             <li class="page-item">
                                                <a href="#" class="page-link">4</a>
                                             </li>
                                             <li class="page-item">
                                                <a href="#" class="page-link">5</a>
                                             </li>
                                             <li class="page-item">
                                                <a href="#" class="page-link">6</a>
                                             </li>
                                             <li class="page-item next">
                                                <a href="#" class="page-link">
                                                <i class="next"></i>
                                                </a>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php include(__DIR__ . '/components/footer.php') ?>

                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php include(__DIR__ . '/requirements/footer.php') ?>
   </body>
</html>