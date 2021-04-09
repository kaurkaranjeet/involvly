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
                icon: "UsersIcon",
                slug: 'all-teachers',
                i18n: 'List'
            },

            {
                url: '/apps/user/allstudents',
                name: 'Students List',
                icon: "UsersIcon",
                slug: 'all-students',
                i18n: 'List'
            },
            {
                url: '/apps/user/allparents',
                name: 'Parents List',
                icon: "UsersIcon",
                slug: 'all-parents',
                i18n: 'List'
            },
            {
                url: '/apps/user/listofschooladmins',
                name: 'School Admin List',
                icon: "UsersIcon",
                slug: 'list-admins-school',
                i18n: 'List'
            }

        ]
    },
    {
        url: "/apps/report/users",
        name: "Report Users",
        slug: "report-users",
        icon: "UsersIcon"

    },
]