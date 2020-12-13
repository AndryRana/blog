<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Tags
                        <Button @click="addModal = true"
                            ><Icon type="md-add-circle" /> Add Admin</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User type</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr
                                v-for="(user, i) in users"
                                :key="i"
                                v-if="users.length"
                            >
                                <td>{{ user.id }}</td>
                                <td class="_table_name">
                                    {{ user.fullName }}
                                </td>
                                <td >
                                    {{ user.email }}
                                </td>
                                <td>{{ user.userType }}</td>
                                <td>{{ user.created_at }}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(user, i)"
                                        >Edit</Button
                                    >
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(user, i)"
                                        :loading="user.isDeleting"
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
                    title="Add admin"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="space">
                        <Input type="text" v-model="data.fullName"  placeholder="Full name" />
                    </div>
                    <div class="space">
                        <Input type="email" v-model="data.email" placeholder="Email" />
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password" />
                    </div>
                    <div class="space">
                        <Select v-model="data.userType"  placeholder='Select admin type'>
                            <Option value="Admin">Admin</Option>
                            <Option value="Editor">Editor</Option>
                        </Select>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="addAdmin"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >
                            {{ isAdding ? "Adding..." : "Add admin" }}</Button
                        >
                    </div>
                </Modal>

                <!-- tag editing modal -->
                <Modal
                    v-model="editModal"
                    title="Edit tag"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input v-model="editData.tagName" placeholder="" />

                    <div slot="footer">
                        <Button type="default" @click="editModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="editTag"
                            :disabled="isAdding"
                            :loading="isAdding"
                        >
                            {{ isAdding ? "Editing..." : "Edit tag" }}</Button
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
                fullName: '',
                email: '',
                password: '',
                userType: 'Admin'
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            users: [],
            editData: {
                tagName: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleing: false,
            deleteItem: {},
            deletingIndex: -1
        };
    },

    methods: {
        async addAdmin() {
            if (this.data.fullName.trim() == "")
                return this.e("Full name is required");
            if (this.data.email.trim() == "")
                return this.e("Email is required");
            if (this.data.password.trim() == "")
                return this.e("Password is required");
            if (this.data.userType.trim() == "")
                return this.e("User type is required");
            const res = await this.callApi("post", "app/create_user", this.data);
            if (res.status === 201) {
                this.tags.unshift(res.data);
                this.s("Admin user has been added successfully!");
                this.addModal = false;
                this.data.fullName = "";
                this.data.email = "";
                this.data.password = "";
            } else {
                if(res.status==422){
                    for(let i in res.data.errors){
                        this.e( res.data.errors[i][0])
                    }
                }else{
                    this.swr();
                }
            }
        },
        async editTag() {
            if (this.editData.tagName.trim() == "")
                return this.e("tag name is required");
            const res = await this.callApi(
                "post",
                "app/edit_tag",
                this.editData
            );
            if (res.status === 200) {
                this.tags[this.index].tagName = this.editData.tagName;
                this.s("Tag has been edited successfully!");
                this.editModal = false;
            } else {
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
                        this.e(res.data.errors.tagName[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },

        showEditModal(tag, index) {
            let obj = {
                id: tag.id,
                tagName: tag.tagName
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
        showDeletingModal(tag, i) {
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_tag",
                data: tag,
                deletingIndex: i,
                isDeleted: false
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            console.log("delete method called");
            // this.deleteItem = tag;
            // this.deletingIndex = i;
            // this.showDeleteModal = true;
        }
    },

    async created() {
        const res = await this.callApi("get", "/app/get_users");
        if (res.status === 200) {
            this.users = res.data;
        } else {
            this.swr();
        }
    },

    computed: {
        ...mapGetters(["getDeleteModalObj"])
    },

    watch: {
        getDeleteModalObj(obj) {
            console.log(obj);
            if (obj.isDeleted) {
                this.tags.splice(this.deletingIndex, 1);
            }
        }
    }
};
</script>
