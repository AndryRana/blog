<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Role Management
                        <Button @click="addModal = true"  v-if="isWritePermitted"
                            ><Icon type="md-add-circle" /> Add a new role</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Role type</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr
                                v-for="(role, i) in roles"
                                :key="i"
                                v-if="roles.length"
                            >
                                <td>{{ role.id }}</td>
                                <td class="_table_name">
                                    {{ role.roleName }}
                                </td>
                                <td>{{ role.created_at }}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(role, i)"
                                         v-if="isUpdatePermitted"
                                        >Edit</Button
                                    >
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(role, i)"
                                        :loading="role.isDeleting"
                                         v-if="isDeletePermitted"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>

                <!-- tag adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="data.roleName" placeholder="Role name" />

                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="add"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >
                            {{ isAdding ? "Adding..." : "Add Role" }}</Button
                        >
                    </div>
                </Modal>

                <!-- tag editing modal -->
                <Modal
                    v-model="editModal"
                    title="Edit role"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input
                        v-model="editData.roleName"
                    />

                    <div slot="footer">
                        <Button type="default" @click="editModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="edit"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >
                            {{ isAdding ? "Editing..." : "Edit role" }}</Button
                        >
                    </div>
                </Modal>

                <!-- Delete alaert Modal -->
                <!-- <Modal v-model="showDeleteModal" width="360">
          <p slot="header" style="color: #f60; text-align: center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
          </p>
          <div style="text-align: center">
            <p>Are you sure you want to delete tag?</p>
          </div>
          <div slot="footer">
            <Button
              type="error"
              size="large"
              long
              :loading="isDeleing"
              :disabled="isDeleing"
              @click="deleteTag"
              >Delete</Button
            >
          </div>
        </Modal> -->
                <delete-modal></delete-modal>
            </div>
        </div>
    </div>
</template>

<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";
export default {
    components: { deleteModal },

    data() {
        return {
            data: {
                roleName: ""
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            roles: [],
            editData: {
                roleName: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleing: false,
            deleteItem: {},
            deletingIndex: -1,
        };
    },

    methods: {
        async add() {
            if (this.data.roleName.trim() == "")
                return this.e("Role name is required");
            const res = await this.callApi("post", "app/create_role", this.data);
            if (res.status === 200) {
                this.roles.unshift(res.data);
                this.s("Role has been added successfully!");
                this.addModal = false;
                this.data.roleName = "";
            } else {
                this.swr();
            }
        },
        async edit() {
            if (this.editData.roleName.trim() == "")
                return this.e("Role name is required");
            const res = await this.callApi(
                "post",
                "app/edit_role",
                this.editData
            );
            if (res.status === 200) {
                this.roles[this.index].roleName = this.editData.roleName;
                this.s("Role has been edited successfully!");
                this.editModal = false;
            } else {
                if (res.status == 422) {
                    if (res.data.errors.roleName) {
                        this.e(res.data.errors.roleName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },

        showEditModal(role, index) {
            let obj = {
                id: role.id,
                roleName: role.roleName
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        // async deleteTag() {
        //     this.isDeleing = true;
        //     const res = await this.callApi(
        //         "post",
        //         "app/delete_tag",
        //         this.deleteItem
        //     );
        //     if (res.status === 200) {
        //         this.tags.splice(this.deletingIndex, 1);
        //         this.s("Tag has been deleted successfully!");
        //     } else {
        //         this.swr();
        //     }
        //     this.isDeleing = false;
        //     this.showDeleteModal = false;
        // },
        showDeletingModal(role, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_role",
                data: role,
                deletingIndex: i,
                isDeleted: false
            }
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            console.log("delete method called");
            // this.deleteItem = tag;
            // this.deletingIndex = i;
            // this.showDeleteModal = true;
        }
    },

    async created() {
        const res = await this.callApi("get", "/app/get_roles");
        
        if (res.status === 200) {
            this.roles = res.data;
        } else {
            this.swr();
        }
    },

    computed: {
        ...mapGetters(['getDeleteModalObj'])
    },

    watch: {
        getDeleteModalObj(obj) {
            console.log(obj);
            if(obj.isDeleted){
                this.roles.splice(this.deletingIndex, 1)
            }
        }
    }
};
</script>
