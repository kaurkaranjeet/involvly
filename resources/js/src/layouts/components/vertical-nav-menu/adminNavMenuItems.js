/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default [{
        url: "/dashboard",
        name: "Home",
        slug: "dashboard",
        icon: "HomeIcon"

    },

    {
        url: null,
        name: 'Users',
        icon: 'UserIcon',
        i18n: 'User',
        submenu: [{
                url: '/apps/user/allteachers',
                name: 'Teachers List',
                slug: 'all-teachers',
                i18n: 'List'
            },

            {
                url: '/apps/user/allstudents',
                name: 'Student List',
                slug: 'all-students',
                i18n: 'List'
            },
            {
                url: '/apps/user/allparents',
                name: 'Parents List',
                slug: 'all-parents',
                i18n: 'List'
            },
            {
                url: '/apps/user/listofschooladmins',
                name: 'School Admin List',
                slug: 'list-admins-school',
                i18n: 'List'
            }

        ]
    }
]