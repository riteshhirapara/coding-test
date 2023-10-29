import { defineStore } from 'pinia'

export const useKanbanStore = defineStore('kanban', {
  state: () => {
    return {
        hoveredName: 'nothing',
        editing: false,
        selectedTask: null,
        phases: [],
        users: [],
        taskStatistics: [],
        labels: [],
        creatingTask: false,
        selectLabel: false,
        deletingTask: false,
        deleteTaskId: '',
        taskDetails: false,
        dragging: false,
        editTask: false,
        creatingTaskProps: {
            name: "",
            due_date: new Date(),
            phase_id: null,
            user_id: null,
            labels: [],
        },
        self: null,
    }
  },
  actions: {
    unhoverTask() {
      this.hoveredName = 'nothing'
    },
    selectTask(task) {
        this.selectedTask = task
    },
    unselectTask(task) {
        this.selectedTask = null
    },
    hasSelectedTask() {
        return this.selectedTask !== null
    },
  },
})
