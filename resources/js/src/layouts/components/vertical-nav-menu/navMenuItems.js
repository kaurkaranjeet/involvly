/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default [
  {
    url: "/",
    name: "Home",
    slug: "home",
    icon: "HomeIcon"

  },
  
        {
        url: null,
        name: 'User',
        icon: 'UserIcon',
        i18n: 'User',
        submenu: [
          {
            url: '/apps/user/user-list',
            name: 'List',
            slug: 'app-user-list',
            i18n: 'List'
          },
         
        ]
      }
]
