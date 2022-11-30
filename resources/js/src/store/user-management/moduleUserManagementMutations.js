/*=========================================================================================
  File Name: moduleCalendarMutations.js
  Description: Calendar Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
    SET_USERS(state, users) {
        state.users = users
    },
    REMOVE_RECORD(state, itemId) {
        const userIndex = state.users.findIndex((u) => u.id === itemId)
        state.users.splice(userIndex, 1)
    },
    REMOVE_RECORD_ADMIN(state, itemId) {
        const userIndex = state.adminusers.findIndex((u) => u.id === itemId)
        state.adminusers.splice(userIndex, 1)
    },
    REMOVE_RECORD_REPORTED(state, itemId) {
        const userIndex = state.reportusers.findIndex((u) => u.to_detail.id === itemId)
        state.reportusers.splice(userIndex, 1)
    },

    SET_ADMIN_USERS(state, adminusers) {
        state.adminusers = adminusers
    },

    SET_SCHOOL_ADMINS(state, schooladmins) {
        state.school_admins = schooladmins
    },

    SET_REPORT_USERS(state, reportusers) {
        state.report_users = reportusers
    },

    SET_CLASSES(state, classes) {
        state.classes = classes
    }
    

}