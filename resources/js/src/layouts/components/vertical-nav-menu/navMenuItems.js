/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default [{
        url: "/",
        name: "Home",
        slug: "home",
        icon: "HomeIcon"

    },

    {
        url: null,
        name: 'Users',
        icon: 'UserIcon',
        i18n: 'User',
        submenu: [{
                url: '/apps/user/user-list',
                name: 'Teachers List',
                icon: "UsersIcon",
                slug: 'app-user-list',
                i18n: 'List'
            },

            {
                url: '/apps/user/listofstudents',
                name: 'Students List',
                 icon: "UsersIcon",
                slug: 'list-students',
                i18n: 'List'
            },
            {
                url: '/apps/user/listofparents',
                name: 'Parents List',
                icon: "UsersIcon",
                slug: 'list-parents',
                i18n: 'List'
            }

        ]
    },
    {
        url: null,
        name: 'Classes ',
        icon: 'UsersIcon',
        i18n: 'Class',
        submenu: [{
                url: '/apps/class/class-list',
                name: 'List of Classes',
                    icon: "CircleIcon",
                slug: 'app-class-list',
                i18n: 'List'
            },
            {
                url: '/apps/subject/subject-list',
                name: 'List of Subjects',
                 icon: "CircleIcon",
                slug: 'app-subject-list',
                i18n: 'List'
            },

        ]
    }
]