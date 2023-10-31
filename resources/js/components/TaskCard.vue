<template>
    <div
        class="w-full text-gray-900 shadow-md rounded-lg p-3 pb-8 mb-4 relative flex flex-col"
        :class="props.is_priority ? 'bg-green-50' : 'bg-white'"
        @mouseenter="kanban.hoveredName = task.name"
        @mouseleave="kanban.unhoverTask()"
        @click="kanban.selectTask(task)"
    >
        <div class="text-xs text-gray-500 flex justify-between">
            <div class="flex gap-1 items-center">
                <CalendarDaysIcon class="h-4 w-4"></CalendarDaysIcon>
                {{ formattedDate(task.updated_at) }}
            </div>
            <div :class="`${task.labels.class} my-2 h-4 text-white bg-red-600 w-fit px-5 py-2 border rounded-md border-solid`"></div>

        </div>
        <span class="cursor-pointer" v-if="!task.editing">
            {{ task.name }}
        </span>
        <input
            type="text"
            class="peer block w-full border-0 bg-gray-50 py-1.5 text-gray-900 focus:ring-0 sm:text-sm sm:leading-6"
            v-if="task.editing"
            @blur="saveChanges(task)"
            @keyup.enter="saveChanges(task)"
            v-model="task.name"
        />
        <br />
        <div
            class="text-xs text-gray-500 absolute bottom-2 cursor-pointer"
            @click="editTask(task)"
        >
            {{ task.user.name }}
        </div>
        <img
            class="w-10 h-10 shadow-lg rounded-full absolute bottom-0 right-0 -mr-1 -mb-2 border-2 border-white cursor-pointer"
            :src="getAvatar()"
            :alt="task.user.name"
        />
    </div>
</template>

<script setup>
import { useKanbanStore } from "../stores/kanban";
import { sha256 } from "js-sha256";
import { CalendarDaysIcon } from "@heroicons/vue/20/solid";

const kanban = useKanbanStore();

const getAvatar = function () {
    if (props.task.user.profile_picture_url !== null) {
        return props.task.user.profile_picture_url;
    } else {
        return (
            "https://www.gravatar.com/avatar/" +
            sha256(String(props.task.user.email).trim().toLowerCase()) +
            "?size=40"
        );
    }
};

const monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];

const formattedDate = function (date) {
    const dateParts = date.split("T")[0].split("-");
    const year = dateParts[0];
    const month = dateParts[1];
    const day = dateParts[2];

    return `${day} ${monthNames[parseInt(month) - 1]}`;
};

const saveChanges = async (task) => {
    await axios
        .put(`/api/tasks/${task.id}`, { name: task.name })
        .then((response) => {
            task.editing = false;
        })
        .catch((error) => {
            console.error(
                "There was an error fetching the logged in user!",
                error
            );
        });
};
const editTask = function (task) {
    task.editing = false;
    kanban.creatingTask = true;
    kanban.editTask = true;
    kanban.creatingTaskProps = task;
};

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    is_priority: {
        type: Boolean,
        required: true,
    },
});
</script>
