/*=========================================================================================
  File Name: moduleCalendarMutations.js
  Description: Calendar Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
    SET_SCHOOL_SUBJECTS(state, schoolsubjects) {
        state.schoolsubjects = schoolsubjects
    },
    REMOVE_SCHOOL_SUBJECTS(state, subjectId) {
        const subjectIndex = state.schoolsubjects.findIndex((u) => u.id === subjectId)
        state.schoolsubjects.splice(subjectIndex, 1)
    }
}