<template>
    <div
        class="max-w-7xl flex-1 mx-auto flex flex-col overflow-auto sm:px-6 lg:px-8"
        style="height: inherit"
    >
        <div class="w-full mb-6 flex">
            <Teleport to="body">
                <generic-modal
                    :show="kanban.creatingTask"
                    @close="kanban.creatingTask = false"
                    key="createTaskModal"
                >
                    <div class="mt-3 sm:mt-2">
                        <DialogTitle
                            as="h3"
                            class="mb-6 text-base font-semibold leading-6 text-gray-900"
                            >Create a new task</DialogTitle
                        >

                        <!-- Task description -->
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Task title</label
                            >
                            <div class="relative mt-2">
                                <input
                                    type="text"
                                    v-model="kanban.creatingTaskProps.name"
                                    id="name"
                                    class="peer block w-full border-0 bg-gray-50 py-1.5 text-gray-900 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="Make it productive, but also fun!"
                                />
                                <p
                                    v-if="hasError('name')"
                                    class="mt-2 text-sm text-red-600"
                                    id="name-error"
                                >
                                    {{ getError("name") }}
                                </p>
                                <div
                                    class="absolute inset-x-0 bottom-0 border-t border-gray-300 peer-focus:border-t-2 peer-focus:border-blue-600"
                                    aria-hidden="true"
                                />
                            </div>
                        </div>

                        <!-- Members & Phse -->
                        <div class="mt-5 grid gap-4 grid-cols-2">
                            <!-- Members -->
                            <Listbox
                                as="div"
                                v-model="kanban.creatingTaskProps.user_id"
                            >
                                <ListboxLabel
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Assigned to</ListboxLabel
                                >
                                <div class="relative">
                                    <ListboxButton
                                        class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm sm:leading-6"
                                    >
                                        <span class="flex items-center">
                                            <img
                                                :src="
                                                    getAvatar(
                                                        kanban.users[
                                                            kanban
                                                                .creatingTaskProps
                                                                .user_id ||
                                                                kanban.self.id
                                                        ]
                                                    )
                                                "
                                                alt=""
                                                class="h-5 w-5 flex-shrink-0 rounded-full"
                                            />
                                            <span class="ml-3 block truncate">{{
                                                kanban.users[
                                                    kanban.creatingTaskProps
                                                        .user_id ||
                                                        kanban.self.id
                                                ].name
                                            }}</span>
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
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <img
                                                            :src="
                                                                getAvatar(
                                                                    person
                                                                )
                                                            "
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
                                                            >{{
                                                                person.name
                                                            }}</span
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

                            <!-- Phase -->
                            <div>
                                <label
                                    for="taskPhase"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Phase</label
                                >
                                <select
                                    v-model="kanban.creatingTaskProps.phase_id"
                                    id="taskPhase"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                                    <option
                                        v-for="phase in kanban.phases"
                                        :key="phase.id"
                                        :value="phase.id"
                                    >
                                        {{ phase.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="mt-5">
                            <label
                                for="dueDate"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Due Date</label
                            >

                            <VueDatePicker
                                v-model="kanban.creatingTaskProps.due_date"
                                :is-24="false"
                            >
                                <template #am-pm-button="{ toggle, value }">
                                    <button @click="toggle">
                                        {{ value }}
                                    </button>
                                </template>
                            </VueDatePicker>
                        </div>

                        <!-- Label -->
                        <div class="mt-5">
                            <label
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Labels</label
                            >
                            <div class="flex flex-wrap gap-2">
                                <div class="flex flex-wrap gap-2">
                                    <div
                                        :key="kanban.selectedLabel.id"
                                        :class="`${kanban.selectedLabel.class} w-max px-2 py-1 text-sm items-center rounded flex gap-1`"
                                    >
                                        <div>
                                            {{ kanban.selectedLabel.name }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="w-max px-1 py-0.5 text-sm self-center rounded bg-slate-200"
                                    @click="toggle"
                                >
                                    <svg
                                        class="h-6 w-6 text-gray-500"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        @click="onAddLabel()"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" />
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                </div>
                            </div>

                            <ul
                                class="absolute z-10 mt-1 max-h-56 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                :class="{ hidden: !kanban.selectLabel }"
                                tabindex="-1"
                                role="listbox"
                                aria-labelledby="listbox-label"
                                aria-activedescendant="listbox-option-3"
                            >
                                <li
                                    v-for="label in kanban.labels"
                                    :key="label.id"
                                    class="text-gray-900 relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-blue-600 hover:text-white"
                                    id="listbox-option-0"
                                    role="option"
                                    @click="onLabelSelect(label)"
                                >
                                    {{ label.name }}
                                </li>
                            </ul>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <button
                                type="button"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                @click="addCard()"
                            >
                                Add the card!
                            </button>
                        </div>
                    </div>
                </generic-modal>

                <!-- Start Delete Confirm Modal -->
                <generic-modal
                    :show="kanban.deletingTask"
                    key="createTaskModal"
                >
                    <DialogTitle
                        as="h3"
                        class="mb-6 text-base font-semibold leading-6 text-gray-900"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                            >
                                <svg
                                    class="h-6 w-6 text-red-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="text-base font-semibold leading-6 text-gray-900"
                                    id="modal-title"
                                >
                                    Delete account
                                </h3>
                            </div>
                        </div>
                    </DialogTitle>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete board? All of
                                your task which is under this board will be
                                permanently removed. This action cannot be
                                undone.
                            </p>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            type="button"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                            @click="deleteBoard()"
                        >
                            Delete
                        </button>
                        <button
                            type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                            @click="kanban.deletingTask = false"
                        >
                            Cancel
                        </button>
                    </div>
                </generic-modal>

                <!-- End Delete Confirm Modal -->
            </Teleport>
        </div>

        <div
            id="kanban-container"
            class="flex-1 flex overflow-auto scrollbar-hide shadow-lg"
            style="height: inherit"
        >
            <div class="text-gray-900">
                <div
                    class="h-full flex overflow-x-auto overflow-y-auto space-x-4"
                >
                    <task-column
                        v-for="col in kanban.phases"
                        :key="col.id"
                        :phase_id="col.id"
                        :is_priority="col.is_priority"
                        @open-delete-modal="openDeleteBoard"
                        :tasks_count="col.tasks_count"
                        @reorder-change="onReorderChange"
                        @reorder-commit="onReorderCommit"
                    ></task-column>
                </div>
            </div>
        </div>

        <!-- Modal to edit the selected card -->
        <Teleport to="body">
            <generic-modal
                v-if="kanban.hasSelectedTask()"
                @close="kanban.unselectTask()"
                :name="'viewTicket'"
            >
                <div class="relative">
                    <div class="flex justify-center">
                        <XMarkIcon
                            class="w-6 h-6 absolute right-0 bottom-33 hover:cursor-pointer"
                            @click="kanban.unselectTask()"
                        />
                    </div>
                    <div class="flex mt-3 sm:mt-5 gap-4">
                        <div class="w-3/4 max-h-[34rem] overflow-y-auto">
                            <DialogTitle
                                class="cursor-pointer text-2xl font-semibold leading-6 text-gray-900"
                                v-if="!kanban.editTask"
                                @click="editTask(kanban)"
                            >
                                {{ kanban.selectedTask.name }}
                            </DialogTitle>
                            <input
                                type="text"
                                class="peer block w-full border-0 bg-gray-50 py-1.5 text-gray-900 focus:ring-0 sm:text-sm sm:leading-6"
                                v-if="kanban.editTask"
                                @blur="saveChanges(kanban.selectedTask.id)"
                                @keyup.enter="
                                    saveChanges(kanban.selectedTask.id)
                                "
                                v-model="kanban.selectedTask.name"
                            />
                            <div
                                :class="`${
                                    kanban.labels[kanban.selectedTask.label_id].class
                                } my-2 ${
                                    kanban.labelActive ? 'h-10 px-3' : 'h-5 px-7'
                                } text-white bg-red-600 w-fit py-2 border rounded-md border-solid`"
                                @click="
                                    kanban.labelActive = !kanban.labelActive
                                "
                            >
                                <p class="text-sm">{{
                                    kanban.labelActive
                                        ? kanban.labels[kanban.selectedTask.label_id].name
                                        : ""
                                }}</p>
                            </div>

                            <label
                                for="name"
                                class="mt-2 block text-lg font-semibold leading-6 text-gray-900"
                                >Description</label
                            >
                            <div
                                class="text-sm cursor-pointer leading-6 w-50 h-50"
                                v-if="!kanban.editDescription"
                                @click="editDescription(kanban)"
                                v-html="
                                    kanban.selectedTask.description
                                        ? kanban.selectedTask.description
                                        : 'Write Description here...'
                                "
                            ></div>
                            <ckeditor
                                :editor="editor"
                                v-model="kanban.selectedTask.description"
                                :config="{}"
                                v-if="kanban.editDescription"
                            ></ckeditor>
                            <button
                                v-if="kanban.editDescription"
                                type="button"
                                class="inline-flex w-fit justify-center rounded-md bg-blue-600 px-3 py-1 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                @click="saveChanges(kanban.selectedTask.id)"
                            >
                                Save
                            </button>
                            <button
                                v-if="kanban.editDescription"
                                type="button"
                                class="inline-flex w-fit justify-center rounded-md bg-white-600 px-3 py-1 ml-1 text-sm font-semibold border-slate-300 border border-solid text-gray-900 shadow-sm hover:bg-white-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white-600"
                                @click="cancelDescription(kanban)"
                            >
                                Cancel
                            </button>
                        </div>
                        <div class="w-1/4 max-h-[34rem] overflow-y-auto">
                            <div class="flex gap-3">
                                <div>
                                    <label
                                        for="taskPhase"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >Phase</label
                                    >
                                    <select
                                        v-model="kanban.selectedTask.phase_id"
                                        id="taskPhase"
                                        class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        @change="
                                            saveChanges(kanban.selectedTask.id)
                                        "
                                    >
                                        <option
                                            v-for="phase in kanban.phases"
                                            :key="phase.id"
                                            :value="phase.id"
                                            :disabled="
                                                phase.is_priority == 'yes'
                                            "
                                        >
                                            {{ phase.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        for="taskLabel"
                                        class="block text-sm font-medium leading-6 text-gray-900"
                                        >Label</label
                                    >
                                    <select
                                        v-model="kanban.selectedTask.label_id"
                                        id="taskPhase"
                                        class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        @change="
                                            updateLabel(
                                                kanban.selectedTask.id
                                            )
                                        "
                                    >
                                        <option
                                            v-for="phase in kanban.labels"
                                            :key="phase.id"
                                            :value="phase.id"
                                            :disabled="
                                                phase.is_priority == 'yes'
                                            "
                                        >
                                            {{ phase.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-2 border border-solid h-[31rem]">
                                <div class="flex items-center border h-10 pl-2">
                                    Details
                                </div>
                                <div class="p-2">
                                    <Listbox
                                        as="div"
                                        v-model="kanban.selectedTask.user_id"
                                        @click="
                                            saveChanges(kanban.selectedTask.id)
                                        "
                                    >
                                        <ListboxLabel
                                            class="block text-sm font-medium leading-6 text-gray-900"
                                            >Assigned to
                                        </ListboxLabel>
                                        <div class="relative mt-2">
                                            <ListboxButton
                                                class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm sm:leading-6"
                                            >
                                                <span class="flex items-center">
                                                    <img
                                                        :src="
                                                            getAvatar(
                                                                kanban.users[
                                                                    kanban
                                                                        .selectedTask
                                                                        .user_id ||
                                                                        kanban
                                                                            .self
                                                                            .id
                                                                ]
                                                            )
                                                        "
                                                        alt=""
                                                        class="h-5 w-5 flex-shrink-0 rounded-full"
                                                    />
                                                    <span
                                                        class="ml-3 block truncate"
                                                        >{{
                                                            kanban.users[
                                                                kanban
                                                                    .selectedTask
                                                                    .user_id ||
                                                                    kanban.self
                                                                        .id
                                                            ].name
                                                        }}</span
                                                    >
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
                                                        v-slot="{
                                                            active,
                                                            selected,
                                                        }"
                                                    >
                                                        <li
                                                            :class="[
                                                                active
                                                                    ? 'bg-blue-600 text-white'
                                                                    : 'text-gray-900',
                                                                'relative cursor-default select-none py-2 pl-3 pr-9',
                                                            ]"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <img
                                                                    :src="
                                                                        getAvatar(
                                                                            person
                                                                        )
                                                                    "
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
                                                                    >{{
                                                                        person.name
                                                                    }}</span
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
                                </div>
                                <div class="p-2">
                                    <h2
                                        class="block text-md font-medium leading-6 text-gray-900"
                                    >
                                        Due Date
                                    </h2>
                                    <p class="text-sm">
                                        {{ kanban.selectedTask.due_date }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button
                        type="button"
                        class="inline-flex w-20 float-right justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                        @click="deleteCard(kanban.selectedTask.id)"
                    >
                        Delete
                    </button>
                </div>
            </generic-modal>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import { useKanbanStore } from "../stores/kanban";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {
    DialogTitle,
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import {
    CheckIcon,
    ChevronUpDownIcon,
    TrashIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";
import { sha256 } from "js-sha256";

const kanban = useKanbanStore();
const selected = ref(null);
const errors = ref(null);

const editor = ClassicEditor;
const onAddLabel = () => {
    kanban.selectLabel = !kanban.selectLabel;
};
const onLabelSelect = (label) => {
    kanban.selectedLabel = label;
    kanban.selectLabel = false;
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

const getError = function (field) {
    if (errors.value && errors.value[field]) {
        return errors.value[field][0].message;
    }
    return null;
};

const hasError = function (field) {
    if (errors.value && errors.value[field]) {
        return true;
    }
    return false;
};

// Define these functions outside of onMounted so they're in the component's scope
let pos = { top: 0, left: 0, x: 0, y: 0 };
let ele;

const mouseDownHandler = function (e) {
    ele.style.cursor = "grabbing";
    ele.style.userSelect = "none";

    pos = {
        left: ele.scrollLeft,
        top: ele.scrollTop,
        x: e.clientX,
        y: e.clientY,
    };

    document.addEventListener("mousemove", mouseMoveHandler);
    document.addEventListener("mouseup", mouseUpHandler);
};

const mouseMoveHandler = function (e) {
    const dx = e.clientX - pos.x;
    const dy = e.clientY - pos.y;

    ele.scrollTop = pos.top - dy;
    ele.scrollLeft = pos.left - dx;
};

const mouseUpHandler = function () {
    ele.style.cursor = "grab";
    ele.style.removeProperty("user-select");

    document.removeEventListener("mousemove", mouseMoveHandler);
    document.removeEventListener("mouseup", mouseUpHandler);
};

const refreshTasks = async () => {
    try {
        const response = await axios.get("/api/tasks");
        const originalTasks = response.data;
        kanban.phases = originalTasks.reduce((acc, cur) => {
            acc[cur.id] = cur;
            return acc;
        }, {});

        //save labels array in variable
        kanban.labels = originalTasks.reduce((acc, curr) => {
            curr.tasks.forEach((cur) => {
                acc[cur.labels.id] = cur.labels;
            });
            return acc;
        }, {});
    } catch (error) {
        console.error("There was an error fetching the tasks!", error);
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

//Save Card data
const addCard = async () => {
    kanban.creatingTaskProps.label_id = kanban.selectedLabel.id;
    try {
        await axios.post("/api/tasks", kanban.creatingTaskProps);
        kanban.creatingTask = false;
        kanban.creatingTaskProps = {
            name: null,
            phase_id: null,
            user_id: null,
            due_date: new Date(),
            labels: 0,
        };
        await refreshTasks();
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};

const deleteCard = async (id) => {
    try {
        await axios.delete("/api/tasks/" + id);
        await refreshTasks();
        kanban.unselectTask();
    } catch (error) {
        console.error("There was an error deleting the task!", error);
    }
};

const editTask = function (kanban) {
    kanban.editTask = true;
};

const editDescription = function (kanban) {
    kanban.editDescription = true;
};

const cancelDescription = function (kanban) {
    kanban.editDescription = false;
};

const saveChanges = async (id) => {
    try {
        await axios.put("/api/tasks/" + id, kanban.selectedTask);
        kanban.editTask = false;
        kanban.editDescription = false;
        await refreshTasks();
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};
const updateLabel = async (id, value) => {
    try {
        await axios.put("/api/tasks/" + id, {
            label_id: kanban.selectedTask.label_id,
        });
        refreshTasks()
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
    }
};

onMounted(async () => {
    await refreshTasks();
    await refreshUsers();
    await getSelf();

    await nextTick();

    ele = document.getElementById("kanban-container");
    if (ele) {
        ele.style.cursor = "grab";
        ele.addEventListener("mousedown", mouseDownHandler);
    }
});

const openDeleteBoard = async (id) => {
    kanban.deleteTaskId = id;
    kanban.deletingTask = true;
};

const deleteBoard = async () => {
    await axios.delete("/api/boards/" + kanban.deleteTaskId);
    kanban.deletingTask = false;
    refreshTasks();
};
const columnsWithOrder = ref([]);
const onReorderChange = (column) => {
    columnsWithOrder.value?.push(column);
};
const onReorderCommit = async () => {
    if (!columnsWithOrder?.value?.length) {
        return;
    }
    try {
        await axios.post("/api/tasks/reorder", {
            phases: columnsWithOrder.value,
        });
        await refreshTasks();
    } catch (error) {
        console.error("There was an error ordering the task!", error);
    }
};
</script>
<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* For IE, Edge and Firefox */
.scrollbar-hide {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}

/* For Set White SVG For Select ArrowDown */
.svgChange {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}
</style>
