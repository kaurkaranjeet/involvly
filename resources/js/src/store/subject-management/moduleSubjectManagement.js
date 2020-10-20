/*=========================================================================================
  File Name: moduleSubjectManagement.js
  Description: Calendar Module
  ----------------------------------------------------------------------------------------
  Class Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import state from './moduleSubjectManagementState.js'
import mutations from './moduleSubjectManagementMutations.js'
import actions from './moduleSubjectManagementActions.js'
import getters from './moduleSubjectManagementGetters.js'

export default {
    isRegistered: false,
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}