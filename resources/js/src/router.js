/*=========================================================================================
  File Name: router.js
  Description: Routes for vue-router. Lazy loading is enabled.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import Router from 'vue-router'
import auth from '@/auth/authService'
Vue.use(Router)

const router = new Router({
    mode: 'history',
    base: '/',
    scrollBehavior() {
        return { x: 0, y: 0 }
    },
    routes: [

        {
            // =============================================================================
            // MAIN LAYOUT ROUTES
            // =============================================================================
            path: '',
            component: () =>
                import ('./layouts/main/Main.vue'),
            children: [
                // =============================================================================
                // Theme Routes
                // =============================================================================
                {
                    path: '/',
                    name: 'home',
                    component: () =>
                        import ('./views/Home.vue'),
                    meta: {
                        authRequired: true
                    }
                },

                {
                    path: '/dashboard',
                    name: 'dashboard',
                    component: () =>
                        import ('./views/Dashboard.vue'),
                    meta: {
                        authRequired: true
                    }
                },
                {
                    path: '/page2',
                    name: 'page-2',
                    component: () =>
                        import ('./views/Page2.vue')
                },
                //classes menu path
                {
                    path: '/apps/class/class-list',
                    name: 'app-class-list',
                    component: () =>
                        import ('@/views/apps/class/class-list/ClassList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Classes', url: '/apps/class/class-list' },
                            { title: 'List', active: true }
                        ],
                        pageTitle: 'Classes List',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/class/class-add',
                    name: 'app-class-add',
                    component: () =>
                        import ('@/views/apps/class/class-add/ClassAdd.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Classes', url: '/apps/class/class-list' },
                            { title: 'Add', active: true }
                        ],
                        pageTitle: 'Add ClassCode',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/class/class-view/:classId',
                    name: 'app-class-view',
                    component: () =>
                        import ('@/views/apps/class/class-view/ClassView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Classes', url: '/apps/class/class-list' },
                            { title: 'View', active: true }
                        ],
                        pageTitle: 'View ClassCode',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/class/class-edit/:classId',
                    name: 'app-class-edit',
                    component: () =>
                        import ('@/views/apps/class/class-edit/ClassEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Classes', url: '/apps/class/class-list' },
                            { title: 'Edit', active: true }
                        ],
                        pageTitle: 'Class Code Edit',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/class/subject-add/:classId',
                    name: 'app-subject-add',
                    component: () =>
                        import ('@/views/apps/class/subject-add/SubjectAdd.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'Add', active: true }
                        ],
                        pageTitle: 'Add Class subject',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/class/subject-view/:subjectId',
                    name: 'app-subject-view',
                    component: () =>
                        import ('@/views/apps/class/subject-view/SubjectView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'View', active: true }
                        ],
                        pageTitle: 'Subject View',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/class/subject-edit/:subjectId',
                    name: 'app-subject-edit',
                    component: () =>
                        import ('@/views/apps/class/subject-edit/SubjectEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'Edit', active: true }
                        ],
                        pageTitle: 'Subject Edit',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                //assign teacher to subjects
                {
                    path: '/apps/class/assign-teacher/:classId/:subjectId',
                    name: 'app-assign-teacher',
                    component: () =>
                        import ('@/views/apps/class/assign-teacher/AssignTeacher.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Class' },
                            { title: 'Assigned Teacher', active: true }
                        ],
                        pageTitle: 'Assigned Teacher',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                //subjects menu path
                {
                    path: '/apps/subject/subject-list',
                    name: 'app-subject-list',
                    component: () =>
                        import ('@/views/apps/subject/subject-list/SubjectList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'List', active: true }
                        ],
                        pageTitle: 'Subjects List',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/subject/subject-add/',
                    name: 'app-subject-add',
                    component: () =>
                        import ('@/views/apps/subject/subject-add/SubjectAdd.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'Add', active: true }
                        ],
                        pageTitle: 'Subject Add',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/subject/subject-view/:subjectId',
                    name: 'app-subject-view',
                    component: () =>
                        import ('@/views/apps/subject/subject-view/SubjectView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'View', active: true }
                        ],
                        pageTitle: 'Subject View',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/subject/subject-edit/:subjectId',
                    name: 'app-subject-edit',
                    component: () =>
                        import ('@/views/apps/subject/subject-edit/SubjectEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'Edit', active: true }
                        ],
                        pageTitle: 'Subject Edit',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                //users menu path
                {
                    path: '/apps/user/user-list',
                    name: 'app-user-list',
                    component: () =>
                        import ('@/views/apps/user/user-list/UserList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Teachers', url: '/apps/user/user-list' },
                            { title: 'List', active: true }
                        ],
                        pageTitle: 'Teachers List',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/user/user-view/:userId',
                    name: 'app-user-view',
                    component: () =>
                        import ('@/views/apps/user/UserView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Teachers', url: '/apps/user/user-list' },
                            { title: 'Teacher', active: true }
                        ],
                        pageTitle: 'Teacher View',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/user/user-edit/:userId',
                    name: 'app-user-edit',
                    component: () =>
                        import ('@/views/apps/user/user-edit/UserEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Teachers', url: '/apps/user/user-list' },
                            { title: 'Edit', active: true }
                        ],
                        pageTitle: 'Teacher Edit',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/listofstudents',
                    name: 'list-students',
                    component: () =>
                        import ('@/views/apps/user/user-list/StudentList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Students', url: '/apps/user/listofstudents' },
                            { title: 'List', active: true }
                        ],
                        pageTitle: 'Students List',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/user/listofparents',
                    name: 'list-parents',
                    component: () =>
                        import ('@/views/apps/user/user-list/ParentList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Parents', url: '/apps/user/listofparents' },
                            { title: 'List', active: true }
                        ],
                        pageTitle: 'Parents List',
                        rule: 'editor',
                        authRequired: true
                    }
                }
            ],
        },
        // =============================================================================
        // FULL PAGE LAYOUTS
        // =============================================================================
        {
            path: '',
            component: () =>
                import ('@/layouts/full-page/FullPage.vue'),
            children: [
                // =============================================================================
                // PAGES
                // =============================================================================
                {
                    path: '/pages/admin/login',
                    name: 'page-admin-login',
                    component: () =>
                        import ('@/views/pages/AdminLogin.vue')
                },

                {
                    path: '/pages/login',
                    name: 'page-login',
                    component: () =>
                        import ('@/views/pages/Login.vue')
                },

                {
                    path: '/pages/register',
                    name: 'page-register',
                    component: () =>
                        import ('@/views/pages/register/Register.vue'),
                    meta: {
                        rule: 'editor'
                    }
                },
                {
                    path: '/pages/error-404',
                    name: 'page-error-404',
                    component: () =>
                        import ('@/views/pages/Error404.vue')
                },
            ]
        },


        // Redirect to 404 page, if no match found
        {
            path: '*',
            redirect: '/pages/error-404'
        }
    ],
})

router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById('loading-bg')
    if (appLoading) {
        appLoading.style.display = "none";
    }
})
router.beforeEach((to, from, next) => {
    // firebase.auth().onAuthStateChanged(() => {

    // get firebase current user
    // const firebaseCurrentUser = firebase.auth().currentUser

    // if (
    //     to.path === "/pages/login" ||
    //     to.path === "/pages/forgot-password" ||
    //     to.path === "/pages/error-404" ||
    //     to.path === "/pages/error-500" ||
    //     to.path === "/pages/register" ||
    //     to.path === "/callback" ||
    //     to.path === "/pages/comingsoon" ||
    //     (auth.isAuthenticated() || firebaseCurrentUser)
    // ) {
    //     return next();
    // }

    // If auth required, check login. If login fails redirect to login page
    // if (to.meta.authRequired) {
    //     const loggedIn = localStorage.getItem('userInfo');
    //     if (!loggedIn) {
    //         router.push({ path: '/pages/login' })
    //     }

    // }
    // if (to.name == 'page-admin-login') {
    //     return next({ name: 'dashboard' });
    // } else {
    //     return next();
    // }
    if ((to.name !== 'page-login' && to.name !== 'page-register' && to.name !== 'page-admin-login') && !(localStorage.getItem("accessToken"))) next({ name: 'page-login' })

    else if ((to.name === 'page-login' || to.name === 'page-register' || to.name === 'page-admin-login') && (localStorage.getItem("accessToken")) && (localStorage.getItem("role_id") == '1')) next({ name: 'dashboard' })

    else if ((to.name === 'page-login' || to.name === 'page-register' || to.name === 'page-admin-login') && (localStorage.getItem("accessToken")) && (localStorage.getItem("role_id") != '1')) next({ name: 'home' })

    else next()

})


export default router