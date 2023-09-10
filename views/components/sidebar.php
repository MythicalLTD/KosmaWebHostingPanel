<?php
function is_active_page($page_urls)
{
   foreach ($page_urls as $page_url) {
      if (strpos($_SERVER['REQUEST_URI'], $page_url) !== false) {
         return true;
      }
   }
   return false;
}
?>
<!--<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
   <div class="app-brand demo">
     <a href="/" class="app-brand-link">
       <span class="app-brand-text demo menu-text fw-bold">
         <?= $settingsManager->getSetting('name') ?>
       </span>
     </a>
   </div>
   
   <div class="menu-inner-shadow"></div>
   
   <ul class="menu-inner py-1">
     <li class="menu-header small text-uppercase">
       <span class="menu-header-text">Home</span>
     </li>
     <li class="menu-item <?php echo is_active_page(['/']) ? 'active' : ''; ?>">
       <a href="/" class="menu-link">
         <i class="menu-icon tf-icons ti ti-home"></i>
         <div>Dashboard</div>
       </a>
     </li>
     <?php
     if ($sessionManager->getUserInfo("role") == "administrator") {
        ?>
       <li class="menu-header small text-uppercase">
         <span class="menu-header-text">Administration Tools</span>
       </li>
       <li class="menu-item <?php echo is_active_page(['/admin/api']) ? 'active' : ''; ?>">
         <a href="/admin/api" class="menu-link">
           <i class="menu-icon tf-icons ti ti-device-gamepad-2"></i>
           <div>Application API</div>
         </a>
       </li>
       <li
         class="menu-item <?php echo is_active_page(['/admin/users/view', '/admin/users/edit', '/admin/users/new']) ? 'active' : ''; ?>">
         <a href="/admin/users/view" class="menu-link">
           <i class="menu-icon tf-icons ti ti-users"></i>
           <div>Users</div>
         </a>
       </li>
       <li class="menu-item <?php echo is_active_page(['/admin/nodes']) ? 'active' : ''; ?>">
         <a href="/admin/nodes" class="menu-link">
           <i class="menu-icon tf-icons ti ti-network"></i>
           <div>Nodes</div>
         </a>
       </li>
       <li class="menu-item <?php echo is_active_page(['/admin/settings']) ? 'active' : ''; ?>">
         <a href="/admin/settings" class="menu-link">
           <i class="menu-icon tf-icons ti ti-settings"></i>
           <div>Settings</div>
         </a>
       </li>
       <?php
     }
     ?>
   </ul>
   </aside>-->
<div class="d-flex align-items-stretch" id="kt_app_header_menu_wrapper">
   <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
      data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
      data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
      data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
      data-kt-swapper-mode="prepend"
      data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_menu_wrapper'}">
      <div
         class="menu menu-rounded menu-column menu-lg-row menu-title-gray-600 menu-state-dark menu-arrow-gray-400 fw-semibold fw-semibold fs-6 align-items-stretch my-5 my-lg-0 px-2 px-lg-0"
         id="#kt_app_header_menu" data-kt-menu="true">
         <div
            class="menu-item <?php echo is_active_page(['/home']) ? 'here show menu-here-bg' : ''; ?>  menu-lg-down-accordion me-0 me-lg-2"
            onclick="window.location.href='/'">
            <span class="menu-link">
               <span class="menu-icon">
                  <i class="ki-outline ki-home"></i>
               </span>
               <a href="/" class="menu-title">Home</a>
               <span class="menu-arrow d-lg-none"></span>
            </span>
         </div>
         <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            data-kt-menu-offset="22,0" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
            <span class="menu-link">
               <span class="menu-icon">
                  <i class="ki-outline ki-graph-3"></i>
               </span>
               <span class="menu-title">Websites</span>
               <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-rocket fs-2"></i>
                     </span>
                     <span class="menu-title">Projects</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/list.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">My Projects</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/project.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">View Project</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/targets.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Targets</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/budget.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Budget</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/users.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Users</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/files.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Files</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/activity.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Activity</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/projects/settings.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Settings</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-handcart fs-2"></i>
                     </span>
                     <span class="menu-title">eCommerce</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item menu-lg-down-accordion">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Catalog</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div
                           class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/catalog/products.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Products</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/catalog/categories.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Categories</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/catalog/add-product.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Add Product</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/catalog/edit-product.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Edit Product</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/catalog/add-category.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Add Category</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/catalog/edit-category.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Edit Category</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div data-kt-menu-trigger="click" class="menu-item menu-accordion menu-sub-indention">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Sales</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/sales/listing.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Orders Listing</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/sales/details.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Order Details</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/sales/add-order.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Add Order</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/sales/edit-order.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Edit Order</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div data-kt-menu-trigger="click" class="menu-item menu-accordion menu-sub-indention">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Customers</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/customers/listing.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Customers Listing</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/customers/details.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Customers Details</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div data-kt-menu-trigger="click" class="menu-item menu-accordion menu-sub-indention">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Reports</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/reports/view.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Products Viewed</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/reports/sales.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Sales</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/reports/returns.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Returns</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/reports/customer-orders.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Customer Orders</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/ecommerce/reports/shipping.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Shipping</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/ecommerce/settings.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Settings</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-chart fs-2"></i>
                     </span>
                     <span class="menu-title">Support Center</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/support-center/overview.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Overview</span>
                        </a>
                     </div>
                     <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item menu-lg-down-accordion">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Tickets</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div
                           class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/support-center/tickets/list.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Ticket List</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/support-center/tickets/view.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Ticket View</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item menu-lg-down-accordion">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Tutorials</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div
                           class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/support-center/tutorials/list.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Tutorials List</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/support-center/tutorials/post.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Tutorials Post</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/support-center/faq.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">FAQ</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/support-center/licenses.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Licenses</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/support-center/contact.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Contact Us</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-shield-tick fs-2"></i>
                     </span>
                     <span class="menu-title">User Management</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item menu-lg-down-accordion">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Users</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div
                           class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/user-management/users/list.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Users List</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/user-management/users/view.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">View User</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item menu-lg-down-accordion">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Roles</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div
                           class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/user-management/roles/list.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Roles List</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/user-management/roles/view.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">View Roles</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/user-management/permissions.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Permissions</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-phone fs-2"></i>
                     </span>
                     <span class="menu-title">Contacts</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/contacts/getting-started.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Getting Started</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/contacts/add-contact.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Add Contact</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/contacts/edit-contact.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Edit Contact</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/contacts/view-contact.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">View Contact</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-basket fs-2"></i>
                     </span>
                     <span class="menu-title">Subscriptions</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/subscriptions/getting-started.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Getting Started</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/subscriptions/list.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Subscription List</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/subscriptions/add.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Add Subscription</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/subscriptions/view.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">View Subscription</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-briefcase fs-2"></i>
                     </span>
                     <span class="menu-title">Customers</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/customers/getting-started.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Getting Started</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/customers/list.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Customer Listing</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/customers/view.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Customer Details</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-credit-cart fs-2"></i>
                     </span>
                     <span class="menu-title">Invoice Management</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item menu-lg-down-accordion">
                        <span class="menu-link">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Profile</span>
                           <span class="menu-arrow"></span>
                        </span>
                        <div
                           class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/invoices/view/invoice-1.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Invoice 1</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/invoices/view/invoice-2.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Invoice 2</span>
                              </a>
                           </div>
                           <div class="menu-item">
                              <a class="menu-link" href="../../demo43/dist/apps/invoices/view/invoice-3.html">
                                 <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                 </span>
                                 <span class="menu-title">Invoice 3</span>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/invoices/create.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Create Invoice</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-file-added fs-2"></i>
                     </span>
                     <span class="menu-title">File Manager</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/file-manager/folders.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Folders</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/file-manager/files.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Files</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/file-manager/blank.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Blank Directory</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/file-manager/settings.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Settings</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-sms fs-2"></i>
                     </span>
                     <span class="menu-title">Inbox</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/inbox/listing.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Messages</span>
                           <span class="menu-badge">
                              <span class="badge badge-light-success">3</span>
                           </span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/inbox/compose.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Compose</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/inbox/reply.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">View & Reply</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion">
                  <span class="menu-link">
                     <span class="menu-icon">
                        <i class="ki-outline ki-message-text-2 fs-2"></i>
                     </span>
                     <span class="menu-title">Chat</span>
                     <span class="menu-arrow"></span>
                  </span>
                  <div
                     class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/chat/private.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Private Chat</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/chat/group.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Group Chat</span>
                        </a>
                     </div>
                     <div class="menu-item">
                        <a class="menu-link" href="../../demo43/dist/apps/chat/drawer.html">
                           <span class="menu-bullet">
                              <span class="bullet bullet-dot"></span>
                           </span>
                           <span class="menu-title">Drawer Chat</span>
                        </a>
                     </div>
                  </div>
               </div>
               <div class="menu-item">
                  <a class="menu-link" href="../../demo43/dist/apps/calendar.html">
                     <span class="menu-icon">
                        <i class="ki-outline ki-calendar-8 fs-2"></i>
                     </span>
                     <span class="menu-title">Calendar</span>
                  </a>
               </div>
            </div>
         </div>
         <?php
         if ($sessionManager->getUserInfo('role') == "Administrator") {
            ?>
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
               data-kt-menu-offset="22,0" class="menu-item <?php echo is_active_page(['/admin/']) ? 'here show menu-here-bg' : ''; ?> menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
               <span class="menu-link">
                  <span class="menu-icon">
                     <i class="ki-outline ki-setting-2"></i>
                  </span>
                  <span class="menu-title">Admin</span>
                  <span class="menu-arrow d-lg-none"></span>
               </span>
               <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                  <div class="menu-item">
                     <a class="menu-link" href="/admin" target="_blank"
                        title="Check the statistics of your web hosting panel and your nodes!" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                           <i class="ki-outline ki-rocket fs-2"></i>
                        </span>
                        <span class="menu-title">Statistics</span>
                     </a>
                  </div>
                  <div class="menu-item">
                     <a class="menu-link" href="/admin/users" target="_blank"
                        title="You can see a list of your users and also have some cool administrative tools."
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                           <i class="ki-outline ki-user fs-2"></i>
                        </span>
                        <span class="menu-title">Users</span>
                     </a>
                  </div>
                  <div class="menu-item">
                     <a class="menu-link" href="/admin/nodes" target="_blank"
                        title="Here you can manage your nodes and their configuration!" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                           <i class="ki-outline ki-abstract-26 fs-2"></i>
                        </span>
                        <span class="menu-title">Nodes</span>
                     </a>
                  </div>
                  <!--<div class="menu-item">
                     <a class="menu-link" href="https://preview.keenthemes.com/metronic8/demo43/layout-builder.html"
                        title="Build your layout and export HTML for server side integration" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                           <i class="ki-outline ki-switch fs-2"></i>
                        </span>
                        <span class="menu-title">Layout Builder</span>
                     </a>
                  </div>-->
                  <div class="menu-item">
                     <a class="menu-link" href="/admin/images" target="_blank"
                        title="Here you can manage your images and their configuration!" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                        <span class="menu-icon">
                           <i class="ki-outline ki-code fs-2"></i>
                        </span>
                        <span class="menu-title">Images</span>
                     </a>
                  </div>
               </div>
            </div>
            <?php
         }
         ?>

      </div>
   </div>
</div>