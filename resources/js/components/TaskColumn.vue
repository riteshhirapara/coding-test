<template>
    <div class="w-[300px] bg-sky-950 rounded-lg shadow-lg">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <span class="flex gap-2">
                    <h2
                        class="inline-flex items-center text-lg text-zinc-100 font-black mb-3"
                    >
                        {{ kanban.phases[props.phase_id].name }}
                        <span
                            class="inline-flex items-center justify-center w-4 h-4 ml-2 text-xs font-semibold text-white rounded-full"
                            style="background-color: #4c687b"
                        >
                            {{ props.tasks_count }}
                        </span>
                    </h2>
                </span>
                <div class="flex items-center">
                    <PlusIcon
                        @click="createTask()"
                        class="mb-3 h-6 w-6 text-white hover:cursor-pointer"
                        aria-hidden="true"
                    />
                    <TrashIcon
                        @click="openDeleteBoard(props.phase_id)"
                        class="mb-3 h-5 w-5 text-white hover:cursor-pointer"
                    ></TrashIcon>
                </div>
            </div>
            <Draggable
                class="drop-zone overflow-y-auto pr-3 -mx-[15px] pl-[15px] space-y-3 mb-4"
                style="height: calc(100vh - 330px)"
                v-model="kanban.phases[props.phase_id].tasks"
                tag="div"
                group="kanban.phases[props.phase_id].tasks"
                item-key="id"
                drag-class="drag"
                ghost-class="ghost"
                draggable=".item"
                @change="onReorderTasks"
                @end="onReorderEnds"
            >
                <template #item="{ element }">
                    <task-card
                        :class="(props.phase_name !== 'Done') ?'item' : ''"
                        v-if="kanban.phases[props.phase_id].tasks.length > 0"
                        :task="element"
                        :phase_name="props.phase_name"
                    />
                </template>
            </Draggable>
            <!-- A card to create a new task -->
            <div
                class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
                @click="createTask()"
                v-if="props.phase_id !== 4"
            >
                <span>Create a new task</span>
                <PlusIcon class="h-6 w-6" aria-hidden="true" />
            </div>
        </div>
    </div>
</template>

<script setup>
// get the props
import { useKanbanStore } from "../stores/kanban";
import { ref, watchEffect, defineEmits } from "vue";
import { PlusIcon, TrashIcon } from "@heroicons/vue/20/solid";
import cloneDeep from "lodash.clonedeep";
import Draggable from "vuedraggable";

const kanban = useKanbanStore();
const emit = defineEmits();

const props = defineProps({
    phase_id: {
        type: Number,
        required: true,
    },
    phase_name: {
        type: String,
        required: true,
    },
    tasks_count: {
        type: Number,
        required: true,
    },
});


const tasks = ref(kanban.phases);

watchEffect(() => {
    tasks.value = kanban.phases;
});

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
};

const onReorderTasks = () => {
    const cloned = cloneDeep(kanban.phases[props.phase_id].tasks);

    const tasksWithOrder = [
        ...cloned?.map((task, index) => ({
            id: task.id,
            position: index * 1000 + 1000,
        })),
    ];

    emit("reorder-change", {
        id: props?.phase_id,
        tasks: tasksWithOrder,
    });
};

const onReorderEnds = () => {
    emit("reorder-commit");
};

const openDeleteBoard = async (id) => {
    emit("open-delete-modal", id);
};
const openTaskDetailsModal = async (task) => {
    emit("open-task-details-modal", task);
};
</script>
