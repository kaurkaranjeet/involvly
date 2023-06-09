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
                        authRequired: true,
                        rule: 'editor'

                    }
                },

                {
                    path: '/dashboard',
                    name: 'dashboard',
                    component: () =>
                        import ('./views/Dashboard.vue'),
                    meta: {
                        authRequired: true,
                        rule: 'admin'
                    }
                },
                {
                    path: '/page2',
                    name: 'page-2',
                    component: () =>
                        import ('./views/Page2.vue')
                },

                // Search Teacher Tab
    
                {
                    path: '/apps/user/teacher-list',
                    name: 'app-search-list',
                    component: () =>
                        import ('@/views/apps/user/user-list/SearchTeacher.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            // { title: 'Teachers', url: '/apps/user/user-list' },  
                            { title: 'Teachers', active: true }
                        ],
                        pageTitle: 'Search a Contractual Teachers',  
                        rule: 'editor',
                        authRequired: true
                    }
                },
                // classes side menu path
                {
                    path: '/apps/class/class-list',
                    name: 'app-class-list',
                    component: () =>
                        import ('@/views/apps/class/class-list/ClassList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            // { title: 'Classes', url: '/apps/class/class-list' },
                            { title: 'Classes', active: true }
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
                        pageTitle: 'Add Class Code',
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
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Class Code',
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
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Class Code',
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
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Subject ',
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
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Subject',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                //classes side menu path -- assign teacher to subjects
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
                //classes side menu path -- subjects 
                {
                    path: '/apps/subject/subject-list',
                    name: 'app-subject-list',
                    component: () =>
                        import ('@/views/apps/subject/subject-list/SubjectList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            // { title: 'Subjects', url: '/apps/subject/subject-list' },
                            { title: 'Subjects', active: true }
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
                        pageTitle: 'Add Subject ',
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
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Subject ',
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
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Subject',
                        rule: 'editor',
                        authRequired: true
                    }
                },
               
                //Contractual Teachers side menu path
                 {
                    path: '/apps/user/contractual-list',
                    name: 'app-contractual-list',
                    component: () =>
                        import ('@/views/apps/user/user-list/ContractualTeacher.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            // { title: 'Teachers', url: '/apps/user/user-list' },  
                            { title: 'Teachers', active: true }
                        ],
                        pageTitle: 'Contractual List',  
                        rule: 'editor',
                        authRequired: true
                    }
                },
                 //teachers side menu path
                 {
                    path: '/apps/user/user-list',
                    name: 'app-user-list',
                    component: () =>
                        import ('@/views/apps/user/user-list/UserList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            // { title: 'Teachers', url: '/apps/user/user-list' },
                            { title: 'Teachers', active: true }
                        ],
                        pageTitle: 'Full-time Teacher List',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                //Full-time Teachers side menu path
                {
                    path: '/apps/user/user-list',
                    name: 'app-user-list',
                    component: () =>
                        import ('@/views/apps/user/user-list/UserList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            // { title: 'Teachers', url: '/apps/user/user-list' },  
                            { title: 'Teachers', active: true }
                        ],
                        pageTitle: 'Full-time Teacher List',  
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
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Teacher Details',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/admin/teacher-view/:userId',
                    name: 'admin-teacher-view',
                    component: () =>
                        import ('@/views/apps/user/UserAdminView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Dashboard', url: '/dashboard' },
                            { title: 'Teachers', url: '/apps/user/allteachers' },
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Teacher Details',
                        rule: 'admin',
                        authRequired: true
                    }
                },


                {
                    path: '/apps/user/student-view/:userId',
                    name: 'app-student-view',
                    component: () =>
                        import ('@/views/apps/user/StudentView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Students', url: '/apps/user/listofstudents' },
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Student Details',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/admin-student-view/:userId',
                    name: 'admin-student-view',
                    component: () =>
                        import ('@/views/apps/user/StudentAdminView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            { title: 'Students', url: '/apps/user/allstudents' },
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Student Details',
                        rule: 'admin',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/parent-view/:userId',
                    name: 'app-parent-view',
                    component: () =>
                        import ('@/views/apps/user/ParentsView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Parents', url: '/apps/user/listofparents' },
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Parent Details',
                        rule: 'editor',
                        authRequired: true
                    }
                },


                {
                    path: '/apps/user/adminparent-view/:userId',
                    name: 'adminparent-view',
                    component: () =>
                        import ('@/views/apps/user/ParentsAdminView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            { title: 'Parents', url: '/apps/user/allparents' },
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View Parent Details',
                        rule: 'admin',
                        authRequired: true
                    }
                },


                {
                    path: '/apps/user/schooladmin-view/:userId',
                    name: 'adminparent-view',
                    component: () =>
                        import ('@/views/apps/user/SchoolAdminsView.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            { title: 'School Admins', url: '/apps/user/listofschooladmins' },
                            { title: 'View Details', active: true }
                        ],
                        pageTitle: 'View School Details',
                        rule: 'admin',
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
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Teacher',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/student-edit/:userId',
                    name: 'app-student-edit',
                    component: () =>
                        import ('@/views/apps/user/user-edit/UserEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Students', url: '/apps/user/listofstudents' },
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Student',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/user/parent-edit/:userId',
                    name: 'app-parent-edit',
                    component: () =>
                        import ('@/views/apps/user/user-edit/UserEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Parents', url: '/apps/user/listofparents' },
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Parent',
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
                            // { title: 'Students', url: '/apps/user/listofstudents' },
                            { title: 'Students', active: true }
                        ],
                        pageTitle: 'Students List',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/addstudent',
                    name: 'add-student',
                    component: () =>
                        import ('@/views/apps/user/user-list/StudentAdd.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Students', url: '/apps/user/listofstudents' },
                            { title: 'Add', active: true }
                        ],
                        pageTitle: 'Add Student',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/addnewteacher',
                    name: 'add-teacher',
                    component: () =>
                        import ('@/views/apps/user/user-list/TeacherAdd.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Teachers', url: '/apps/user/user-list' },
                            { title: 'Add', active: true }
                        ],
                        pageTitle: 'Add New Teacher',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/add_new_parent',
                    name: 'add-parent',
                    component: () =>
                        import ('@/views/apps/user/user-list/ParentAdd.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/' },
                            { title: 'Parents', url: '/apps/user/listofparents' },
                            { title: 'Add', active: true }
                        ],
                        pageTitle: 'Add New Parent',
                        rule: 'editor',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/listofschooladmins',
                    name: 'list-admins-school',
                    component: () =>
                        import ('@/views/apps/admin/user-list/SchoolAdminList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'School Admins', url: '/apps/user/listofschooladmins' },
                            { title: 'School Admins', active: true }
                        ],
                        pageTitle: 'School Admin List',
                        rule: 'admin',
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
                            // { title: 'Parents', url: '/apps/user/listofparents' },
                            { title: 'Parents', active: true }
                        ],
                        pageTitle: 'Parents List',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                //Super admin
                //teachers side menu path
                {
                    path: '/apps/user/allteachers',
                    name: 'app-teacher-list',
                    component: () =>
                        import ('@/views/apps/admin/user-list/TeacherList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Teachers', url: '/apps/user/allteachers' },
                            { title: 'Teachers', active: true }
                        ],
                        pageTitle: 'Teachers List',
                        rule: 'admin',
                        authRequired: true
                    }
                },
                // Training Program List
                {
                    path: '/apps/user/alltraining',
                    name: 'app-training-list',
                    component: () =>
                        import ('@/views/apps/admin/user-list/ProgramTeacher.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' }, 
                            { title: 'TrainingProgram', active: true }
                        ],
                        pageTitle: 'Training Program List',
                        rule: 'admin',
                        authRequired: true
                    }
                },

                {
                    path: '/apps/user/allstudents',
                    name: 'app-student-list',
                    component: () =>
                        import ('@/views/apps/admin/user-list/StudentList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Students', url: '/apps/user/allstudents' },
                            { title: 'Students', active: true }
                        ],
                        pageTitle: 'Students List',
                        rule: 'admin',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/user/allparents',
                    name: 'app-parent-list',
                    component: () =>
                        import ('@/views/apps/admin/user-list/ParentList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Parents', url: '/apps/user/allparents' },
                            { title: 'Parents', active: true }
                        ],
                        pageTitle: 'Parents List',
                        rule: 'admin',
                        authRequired: true
                    }
                },

                //change password
                {
                    path: '/apps/profile/changepassword',
                    name: 'profile-edit',
                    component: () =>
                        import ('@/views/apps/profile/ChangePassword.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Parents', url: '/apps/user/allparents' },
                            { title: 'Change Password', active: true }
                        ],
                        pageTitle: 'Change Password',
                        rule: 'editor',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/admin/profile/changepassword',
                    name: 'profile-edit',
                    component: () =>
                        import ('@/views/apps/profile/ChangePassword.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Parents', url: '/apps/user/allparents' },
                            { title: 'Change Password', active: true }
                        ],
                        pageTitle: 'Change Password',
                        rule: 'admin',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/profile/editprofile',
                    name: 'profile-edit',
                    component: () =>
                        import ('@/views/apps/profile/ProfileEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Parents', url: '/apps/user/allparents' },
                            { title: 'Edit Details', active: true }
                        ],
                        pageTitle: 'Edit Profile',
                        rule: 'editor',
                    }
                },
                {
                    path: '/apps/admin/profile/editprofile',
                    name: 'profile-edit',
                    component: () =>
                        import ('@/views/apps/profile/ProfileEdit.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Parents', url: '/apps/user/allparents' },
                            { title: 'Profile', active: true }
                        ],
                        pageTitle: 'Edit Profile',
                        rule: 'admin',
                        authRequired: true
                    }
                },
                {
                    path: '/apps/report/users',
                    name: 'app-report-users',
                    component: () =>
                        import ('@/views/apps/admin/report/ReportList.vue'),
                    meta: {
                        breadcrumb: [
                            { title: 'Home', url: '/dashboard' },
                            // { title: 'Users', url: '/apps/report/users' },
                            { title: 'Users', active: true }
                        ],
                        pageTitle: 'Report Users',
                        rule: 'admin',
                        authRequired: true
                    }
                },
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
                    path: '/pages/forgot/password',
                    name: 'page-forgot-password',
                    component: () =>
                        import ('@/views/pages/ForgotPassword.vue')
                },
                {
                    path: '/reset/password',
                    name: 'page-reset-password',
                    component: () =>
                        import ('@/views/pages/ResetPassword.vue'),
                    meta: {
                        rule: 'editor'
                    }
                },
                {
                    path: '/pages/forgot/adminpassword',
                    name: 'page-forgot-adminpassword',
                    component: () =>
                        import ('@/views/pages/adminForgotPassword.vue')
                },
                {
                    path: '/reset/adminpassword',
                    name: 'page-reset-adminpassword',
                    component: () =>
                        import ('@/views/pages/adminResetPassword.vue'),
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
    if ((to.name !== 'page-login' && to.name !== 'page-forgot-password' && to.name !== 'page-forgot-adminpassword' && to.name !== 'page-reset-password' && to.name !== 'page-reset-adminpassword' && to.name !== 'page-register' && to.name !== 'page-admin-login') && !(localStorage.getItem("accessToken"))) next({ name: 'page-login' })

    else if ((to.name === 'page-admin-login' && to.name === 'page-forgot-password' && to.name === 'page-forgot-adminpassword' && to.name === 'page-reset-password' && to.name === 'page-reset-adminpassword') && (localStorage.getItem("accessToken")) && (localStorage.getItem("role_id") == '1')) next({ name: 'dashboard' })

    else if ((to.name === 'page-login' || to.name === 'page-register') && (localStorage.getItem("accessToken")) && (localStorage.getItem("role_id") != '1')) next({ name: 'home' })

    else next()

})


export default router