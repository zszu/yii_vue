<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 菜单权限管理</el-breadcrumb-item>
                <el-breadcrumb-item>用户列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">
            <div>
                <el-button icon="el-icon-add" @click="handleAdd"  type="success">添加</el-button>
            </div>
            <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="序号" sortable width="150"></el-table-column>
                <el-table-column prop="username" label="用户名" width="160"> </el-table-column>
                <el-table-column prop="role" label="角色"  :formatter="formatRole"></el-table-column>
                <el-table-column label="操作" width="240" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="type ? '添加' : '编辑'" :visible.sync="editVisible" width="30%">
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="用户名">
                    <el-input v-model="form.username"></el-input>
                </el-form-item>

                <el-form-item label="密码">
                    <el-input v-model="form.password"></el-input>
                </el-form-item>
                <el-form-item label="所属角色">
                    <el-select v-model="form.role" placeholder="请选择">
                        <el-option
                                v-for="(item,index) in roleList"
                                :key="index"
                                :label="item"
                                :value="index">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 删除提示框 -->
        <el-dialog title="提示" :visible.sync="delVisible" width="300px" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="delVisible = false">取 消</el-button>
                <el-button type="primary" @click="deleteRow">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tableData: [],
                cur_page: 1,
                multipleSelection: [],
                editVisible: false,
                delVisible: false,
                form: {
                    username: '',
                    password:'',
                    role:'',
                    id:0,
                },
                type: 1,
                id:0,
                roleList:[],
                index:0,
            }
        },
        created() {
            // console.log('created');
            this.getData();
        },

        methods: {
            // 用户列表
            getData(){
                this.$http_.get('user/index',{},(res)=>{
                    // console.log(res);

                    this.tableData = res.data.users;
                    this.roleList = res.data.roles;
                });
            },
            formatRole(row, column) {
                return this.roleList[row.role];
            },
            //添加
            handleAdd(){
                this.form.username = '';
                this.form.role = '';
                this.form.id = 0;
                this.password = '';
                this.id = 0;

                this.type = 1;
                this.editVisible = true;
            },

            //修改
            handleEdit(index, row) {
                this.type = 0;
                this.id = row.id;
                const item = this.tableData[index];
                console.log(item);

                this.form = {
                    username: item.username,
                    role: item.role,
                    id:this.id,
                };
                this.editVisible = true;
            },
            handleDelete(index, row) {
                this.id = row.id;
                this.index = row.index;
                this.delVisible = true;
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },

            // 保存编辑
            saveEdit() {
                // console.log(this.form);
                var url = this.id  ? 'user/update' : 'user/create';

                this.$http_.post(url ,this.form,(res)=>{
                    // console.log(res);
                    if(res.code=='200'){
                        //重新加载数据
                        this.getData();
                        this.$message.success(res.message);
                    }else{
                        this.$message.warning(res.message);
                    }
                });
                this.editVisible = false;
            },
            // 确定删除
            deleteRow(){
                this.$http_.post('user/delete',{id:this.id},(res)=>{
                    if(res.code=='200'){
                        this.$message.success(res.message);
                        this.tableData.splice(this.index, 1);

                    }else{
                        this.$message.warning(res.message);
                    }
                });
                this.delVisible = false;
                // this.tableData.splice(this.type, 1);
            },
        }
    }

</script>

<style scoped>
    .iconfont{
        font-size: 20px;
        /*font-weight: bold;*/
    }
    .handle-box {
        margin-bottom: 20px;
    }

    .handle-select {
        width: 120px;
    }

    .handle-input {
        width: 300px;
        display: inline-block;
    }
    .del-dialog-cnt{
        font-size: 16px;
        text-align: center
    }
    .table{
        width: 100%;
        font-size: 14px;
    }
    .red{
        color: #ff0000;
    }
</style>
