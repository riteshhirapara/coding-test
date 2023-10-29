<template>
    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <div class="w-full">
            <div class="flex flex-row items-end justify-end mb-2 items-end">
                <Listbox as="div" v-model="kanban.creatingTaskProps.user_id">
                    <div class="relative mt-2" style="min-width: 14rem">
                        <ListboxButton
                            class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm sm:leading-6"
                        >
                            <span class="flex items-center">
                                <img
                                    src="
                                    getAvatar(
                                        kanban.users[
                                            kanban.creatingTaskProps.user_id ||
                                                kanban.self.id
                                        ]
                                    )
                                "
                                    alt=""
                                    class="h-5 w-5 flex-shrink-0 rounded-full"
                                />
                                <span class="ml-3 block truncate">ddd</span>
                                <!-- <span class="ml-3 block truncate">{{
                                    kanban.users[
                                        kanban.creatingTaskProps.user_id ||
                                            kanban.self.id
                                    ].name
                                }}</span> -->
                            </span>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                            >
                                <ChevronUpDownIcon
                                    class="h-5 w-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </span>
                        </ListboxButton>

                        <transition
                            leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <ListboxOptions
                                class="absolute z-20 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                            >
                                <ListboxOption
                                    as="template"
                                    v-for="person in kanban.users"
                                    :key="person.id"
                                    :value="person.id"
                                    v-slot="{ active, selected }"
                                >
                                    <li
                                        :class="[
                                            active
                                                ? 'bg-blue-600 text-white'
                                                : 'text-gray-900',
                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                        ]"
                                    >
                                        <div class="flex items-center">
                                            <img
                                                :src="getAvatar(person)"
                                                alt="{{ person.name }}"
                                                class="h-5 w-5 flex-shrink-0 rounded-full"
                                            />
                                            <span
                                                :class="[
                                                    selected
                                                        ? 'font-semibold'
                                                        : 'font-normal',
                                                    'ml-3 block truncate',
                                                ]"
                                                >{{ person.name }}</span
                                            >
                                        </div>

                                        <span
                                            v-if="selected"
                                            :class="[
                                                active
                                                    ? 'text-white'
                                                    : 'text-blue-600',
                                                'absolute inset-y-0 right-0 flex items-center pr-4',
                                            ]"
                                        >
                                            <CheckIcon
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>

                <select
                    v-model="kanban.creatingTaskProps.phase_id"
                    id="timeDuration"
                    class="mt-2 ml-2 block rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6"
                >
                    <option
                        v-for="duration in durations"
                        :key="duration.key"
                        :value="duration.value"
                    >
                        {{ duration.value }}
                    </option>
                </select>
            </div>
            <div class="max-h-[600px] overflow-y-auto">
                <table class="min-w-full rounded-md overflow-hidden">
                    <thead>
                        <tr class="bg-sky-950">
                            <th
                                v-for="column in headerColumns"
                                :key="column.key"
                                :class="`px-6 py-3 text-left text-xs leading-4 font-medium text-white uppercase tracking-wider ${column.class} whitespace-no-wrap`"
                            >
                                {{ column.displayName }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr
                            class="border-b border-gray-300 cursor-pointer"
                            v-for="task in kanban.taskStatistics"
                            :key="task.id"
                            @click="onRawClick(task)"
                        >
                            <td
                                class="px-4 py-4 whitespace-no-wrap text-sm max-w-[10%]"
                            >
                                {{ task.id }}
                            </td>
                            <td
                                class="px-4 py-4 whitespace-no-wrap text-sm max-w-[15%]"
                            >
                                <div class="flex w-full text-sm">
                                    <img
                                        class="w-8 h-8 shadow-lg rounded-full bottom-0 right-0 -mr-2 -mb-2 border-2 border-sky-950"
                                        :src="task.user.image"
                                        :alt="altImageText"
                                    />
                                    <div
                                        class="ml-3 whitespace-no-wrap text-sm mt-auto"
                                    >
                                        {{ task.user.name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm max-w-[20%]">
                                {{ task.task.name }}
                            </td>
                            <td
                                class="px-4 py-4 whitespace-no-wrap text-sm max-w-[10%]"
                            >
                                {{ task.reporter.name }}
                            </td>
                            <td
                                class="px-2 py-3 whitespace-no-wrap text-sm max-w-[15%]"
                            >
                                <span
                                    class="px-2 py-1 text-xs font-semibold leading-none rounded-full text-white whitespace-no-wrap"
                                    :class="getStatusColor(task.status)"
                                >
                                    {{ task.status }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-4 whitespace-no-wrap text-sm max-w-[10%]"
                            >
                                {{ task.created_date }}
                            </td>
                            <td
                                class="px-4 py-4 whitespace-no-wrap text-sm max-w-[10%]"
                            >
                                {{ task.updated_date }}
                            </td>
                            <td
                                class="px-4 py-4 whitespace-no-wrap text-sm max-w-[20%]"
                            >
                                <span
                                    class="px-2 py-1 text-xs font-semibold leading-none rounded-full text-white whitespace-no-wrap"
                                    :class="{
                                        'bg-red-600': task.due,
                                        'bg-gray-400': !task.due,
                                    }"
                                >
                                    Due
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, nextTick } from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { useKanbanStore } from "../stores/kanban";
import {
    CheckIcon,
    ChevronUpDownIcon,
    TrashIcon,
} from "@heroicons/vue/20/solid";
import { sha256 } from "js-sha256";

const kanban = useKanbanStore();
const altImageText = "User Image";

const headerColumns = [
    { displayName: "Key", key: "id", class: "max-w-[10%]" },
    { displayName: "Assignee", key: "user", class: "max-w-[15%]" },
    { displayName: "Tasks", key: "task", class: "max-w-[20%]" },
    { displayName: "Reporter", key: "reporter", class: "max-w-[10%]" },
    { displayName: "Status", key: "status", class: "max-w-[15%]" },
    { displayName: "Created At", key: "created_date", class: "max-w-[10%]" },
    { displayName: "OverDue", key: "updated_date", class: "max-w-[10%]" },
    { displayName: "Due", key: "due", class: "max-w-[10%]" },
];

const durations = [
    {
        value: "This Week",
        key: "this-week",
    },
    {
        value: "This Month",
        key: "this-month",
    },
];

const onRawClick = (task) => {
};

const getAvatar = function (user) {
    if (user.profile_picture_url !== null) {
        return user.profile_picture_url;
    } else {
        return (
            "https://www.gravatar.com/avatar/" +
            sha256(String(user.email).trim().toLowerCase()) +
            "?size=400"
        );
    }
};

const refreshUsers = async () => {
    try {
        const response = await axios.get("/api/users");
        const originalUsers = response.data;
        // rekey originalUsers to use the id property in the objects as the array key
        kanban.users = originalUsers.reduce((acc, cur) => {
            acc[cur.id] = cur;
            return acc;
        }, {});
    } catch (error) {
        console.error("There was an error fetching the users!", error);
    }
};

const getSelf = async () => {
    try {
        const response = await axios.get("/api/user");
        kanban.self = response.data;
        if (kanban.creatingTaskProps.user_id === null) {
            kanban.creatingTaskProps.user_id = kanban.self.id;
        }
        if (kanban.self.profile_picture_url === null) {
            kanban.self.profile_picture_url = getAvatar(kanban.self);
        }
    } catch (error) {
        console.error("There was an error fetching the logged in user!", error);
    }
};

const getTaskStatistics = async () => {
    try {
        // const response = await axios.get("/api/user");
        kanban.taskStatistics = [
            {
                id: 1,
                user: {
                    id: 1,
                    name: "Luke Skywalker",
                    image: "https://www.dependablesolutions.com/wp-content/uploads/2020/05/logo.png",
                },
                task: {
                    id: 1,
                    name: "Try not to lose your lightsaber this time.",
                },
                status: "in-progress",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: true,
            },
            {
                id: 2,
                user: {
                    id: 1,
                    name: "Luke Skywalker",
                    image: "https://www.dependablesolutions.com/wp-content/uploads/2020/05/logo.png",
                },
                task: {
                    id: 2,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "booked",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: false,
            },
            {
                id: 3,
                user: {
                    id: 2,
                    name: "Darth Vader",
                    image: "https://media.istockphoto.com/id/1406153509/photo/3d-illustration-of-smiling-african-american-woman-coco-showing-thumb-up-positive-hand-gesture.jpg?s=1024x1024&w=is&k=20&c=9sTi7sI2jnyaK8RhJ6NKtJNGBYCZss9JU_vQ3T5KpE8=",
                },
                task: {
                    id: 3,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "completed",
                reporter: {
                    id: 1,
                    name: "Luke Skywalker",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: false,
            },
            {
                id: 4,
                user: {
                    id: 3,
                    name: "Princess Leia Organa",
                    image: "https://media.istockphoto.com/id/1398923416/photo/close-up-portrait-of-smiling-businesswoman-ellen-with-arms-crossed-3d-rendering-on-white.jpg?s=2048x2048&w=is&k=20&c=2I07YZn4bj1LqKGO-33GP9ZVc1Dgdgij44iKwDxdg6g=",
                },
                task: {
                    id: 4,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "Booked",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: true,
            },
            {
                id: 1,
                user: {
                    id: 1,
                    name: "Luke Skywalker",
                    image: "https://www.dependablesolutions.com/wp-content/uploads/2020/05/logo.png",
                },
                task: {
                    id: 1,
                    name: "Try not to lose your lightsaber this time.",
                },
                status: "in-progress",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: true,
            },
            {
                id: 2,
                user: {
                    id: 1,
                    name: "Luke Skywalker",
                    image: "https://www.dependablesolutions.com/wp-content/uploads/2020/05/logo.png",
                },
                task: {
                    id: 2,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "booked",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: false,
            },
            {
                id: 3,
                user: {
                    id: 2,
                    name: "Darth Vader",
                    image: "https://media.istockphoto.com/id/1406153509/photo/3d-illustration-of-smiling-african-american-woman-coco-showing-thumb-up-positive-hand-gesture.jpg?s=1024x1024&w=is&k=20&c=9sTi7sI2jnyaK8RhJ6NKtJNGBYCZss9JU_vQ3T5KpE8=",
                },
                task: {
                    id: 3,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "completed",
                reporter: {
                    id: 1,
                    name: "Luke Skywalker",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: false,
            },
            {
                id: 4,
                user: {
                    id: 3,
                    name: "Princess Leia Organa",
                    image: "https://media.istockphoto.com/id/1398923416/photo/close-up-portrait-of-smiling-businesswoman-ellen-with-arms-crossed-3d-rendering-on-white.jpg?s=2048x2048&w=is&k=20&c=2I07YZn4bj1LqKGO-33GP9ZVc1Dgdgij44iKwDxdg6g=",
                },
                task: {
                    id: 4,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "Booked",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: true,
            },
            {
                id: 1,
                user: {
                    id: 1,
                    name: "Luke Skywalker",
                    image: "https://www.dependablesolutions.com/wp-content/uploads/2020/05/logo.png",
                },
                task: {
                    id: 1,
                    name: "Try not to lose your lightsaber this time.",
                },
                status: "in-progress",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: true,
            },
            {
                id: 2,
                user: {
                    id: 1,
                    name: "Luke Skywalker",
                    image: "https://www.dependablesolutions.com/wp-content/uploads/2020/05/logo.png",
                },
                task: {
                    id: 2,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "booked",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: false,
            },
            {
                id: 3,
                user: {
                    id: 2,
                    name: "Darth Vader",
                    image: "https://media.istockphoto.com/id/1406153509/photo/3d-illustration-of-smiling-african-american-woman-coco-showing-thumb-up-positive-hand-gesture.jpg?s=1024x1024&w=is&k=20&c=9sTi7sI2jnyaK8RhJ6NKtJNGBYCZss9JU_vQ3T5KpE8=",
                },
                task: {
                    id: 3,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "completed",
                reporter: {
                    id: 1,
                    name: "Luke Skywalker",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: false,
            },
            {
                id: 4,
                user: {
                    id: 3,
                    name: "Princess Leia Organa",
                    image: "https://media.istockphoto.com/id/1398923416/photo/close-up-portrait-of-smiling-businesswoman-ellen-with-arms-crossed-3d-rendering-on-white.jpg?s=2048x2048&w=is&k=20&c=2I07YZn4bj1LqKGO-33GP9ZVc1Dgdgij44iKwDxdg6g=",
                },
                task: {
                    id: 4,
                    name: "Attend Sith sensitivity training session to work on your anger management.",
                },
                status: "Booked",
                reporter: {
                    id: 2,
                    name: "Darth Vader",
                },
                created_date: "2023/10/01 11:00",
                updated_date: "2023/10/28 11:00",
                due: true,
            },
        ];
        // if (kanban.taskStatistics === null) {
        //     something
        // }
        // if (kanban.taskStatistics.... === null) {
        //     something
        // }
    } catch (error) {
        console.error("There was an error fetching the task Statistics!", error);
    }
};

const getStatusColor = (status) => {
    const statusColors = {
        "in-progress": "bg-sky-600",
        booked: "bg-yellow-700",
        "un-assigned": "bg-gray-400",
        completed: "bg-green-600",
    };

    return statusColors[status] || "bg-gray-400"; // Default to gray if no match is found
};

onMounted(async () => {
    await refreshUsers();
    await getSelf();
    await getTaskStatistics();

    await nextTick();

    // ele = document.getElementById("kanban-container");
    // if (ele) {
    //     ele.style.cursor = "grab";
    //     ele.addEventListener("mousedown", mouseDownHandler);
    // }
});

// onUnmounted(() => {
//     if (ele) {
//         // Clean up the event listener when the component is unmounted.
//         ele.removeEventListener("mousedown", mouseDownHandler);
//     }
// });
</script>
