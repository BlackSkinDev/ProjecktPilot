<template>
    <div class="flex flex-col h-screen bg-blue-600" ref="myComponentRef">
        <main class="flex-1 overflow-hidden">
            <div class="flex flex-col h-full">
                <div class="shrink-0 flex flex-wrap justify-between items-center p-4">
                    <div class="flex flex-col items-start max-w-full relative">
                        <h1
                            ref="heading"
                            class="break-all px-3 py-1.5 text-xl font-bold text-white hover:bg-white/20 rounded-md cursor-pointer border border-none whitespace-pre w-full overflow-hidden text-ellipsis"
                            :class="[isEditing ? 'invisible' : '']"
                            @click="edit"
                        >
                            {{name ? name : '  '}}
                        </h1>
                        <form  v-show="isEditing"  @submit.prevent  class="w-full">
                            <input
                                :style="{width:'300px'}"
                                ref="input"
                                v-model="name"
                                type="text"
                                placeholder="Project name"
                                class="absolute inset-0 max-w-full text-2xl font-bold placeholder-gray-400 px-3 py-1.5 rounded-md focus:ring-2 focus:ring-blue-900"
                                v-on:blur="saveProjectname"
                                v-on:keyup.enter="$event.target.blur()"

                            >
                        </form>
                    </div>
                    <div>
                        <Menu as="div" class="relative z-10">
                            <MenuButton class="bg-white/10 hover:bg-white/20 px-3 py-2 font-medium text-sm text-white rounded-md inline-flex items-center">
                                    <DotsHorizontalIcon class="w-5 h-5"/>
                                    <span class="ml-1">Actions</span>
                            </MenuButton>
                            <transition
                                enter-active-class="transition transform duration-100 ease-out"
                                enter-from-class="opacity-0 scale-90"
                                enter-to-class="opacity-100 scale-100"
                                leave-active-class="transition transform duration-100 ease-in"
                                leave-from-class="opacity-100 scale-100"
                                leave-to-class="opacity-0 scale-90"
                            >
                                <MenuItems class="origin-top-right mt-2 focus:outline-none absolute right-0 bg-white overflow-hidden  shadow-lg border w-32">
                                    <MenuItem v-slot="{active}" class="border-b">
                                        <a href="#" :class="{'bg-gray-100': active}" class="block px-4 py-2 text-sm text-g-700" @click="isCreateFormOpen=true">Create Task</a>
                                    </MenuItem>
                                    <MenuItem v-slot="{active}" class="border-b">
                                        <a href="#" :class="{'bg-gray-100': active}" class="block px-4 py-2 text-sm text-red-500">Delete</a>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
                <div class="flex-1 p-4 overflow-x-auto">
                    <div class="inline-flex h-full space-x-4" v-if="!loading">
                        <StageTaskList
                            v-for="(stage,idx) in stages_tasks"
                            :key="idx" class="w-72 bg-gray-200 max-h-full flex flex-col rounded-md"
                            :stage="stage"
                        />
                    </div>
                    <clip-loader v-else  class="mt-3 mx-auto block" :loading="loading" :color="'black'" :size="'20px'"></clip-loader>
                </div>
            </div>

        </main>
        <ProjectTaskCreateForm :isOpen="isCreateFormOpen" @save="saveTask" @close-modal="isCreateFormOpen=false" ref="createTaskForm"/>
    </div>
</template>
<script>
import Draggable from "vuedraggable"
import {DotsHorizontalIcon,PencilIcon} from "@heroicons/vue/solid"
import {Menu,MenuButton,MenuItem,MenuItems} from '@headlessui/vue'
import {useProjectStore} from "../../store/ProjectStore";
import {mapState,mapActions} from "pinia";
import {Util} from "../../util";
import StageTaskList from "../../components/Project/StageTaskList.vue";
import {FIRST_DEV_STAGE_NAME, TASK_CREATE_SUCCESS_MESSAGE} from "../../constants/constants";
import ProjectTaskCreateForm from "../../components/Project/ProjectTaskCreateForm.vue";
import ClipLoader from "vue-spinner/src/ClipLoader.vue";

export default{
    components: {
        Draggable,
        DotsHorizontalIcon,
        PencilIcon,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        StageTaskList,
        ProjectTaskCreateForm,
        ClipLoader
    },
    data() {
        return {
            isEditing:false,
            isCreateFormOpen:false,
            stages:this.stages_tasks
        }
    },
    methods: {
        ...mapActions(useProjectStore,['fetchProject','updateProject','fetchTasks','saveProjectTask']),
        edit(){
            this.isEditing = true
            this.$nextTick(() => {
                this.$refs.input.select();
            });
        },
        async saveProjectname() {
            if (this.name !== ''){
                this.isEditing = false
                const response = await Util(this.updateProject({name:this.name}))
                if (!response){
                    this.isEditing = true
                    await this.$nextTick(() => {
                        this.$refs.input.select();
                    });
                }
            }
        },
        async saveTask(data) {
            const res = await Util(this.saveProjectTask(data), TASK_CREATE_SUCCESS_MESSAGE, true)
            if(res) {
                this.isCreateFormOpen = false;
                this.$refs.createTaskForm.resetForm();
                const toDoStage = this.stages_tasks.find(stage => stage.name === FIRST_DEV_STAGE_NAME);
                toDoStage.tasks.push(this.newProject);
            }



        },
    },
    async mounted() {
           await  Util(this.fetchProject(this.$route.params.ref)),
           await  Util(this.fetchTasks())

    },
    computed:{
        ...mapState(useProjectStore,{
            project:(state)         => state.project,
            newProject:(state)      => state.newProject,
            loading:(state)         => state.processingRequest
        }),

        name:{
            get(){
                return useProjectStore().project.name;
            },
            set(value){
                return useProjectStore().project.name = value
            }
        },
        stages_tasks:{
            get(){
                return useProjectStore().project_stages_tasks;
            },
            set(value){
                return useProjectStore().project_stages_tasks = value
            }
        },
    },



}
</script>
