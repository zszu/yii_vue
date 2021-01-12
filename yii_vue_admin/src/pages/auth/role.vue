<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 菜单权限管理</el-breadcrumb-item>
                <el-breadcrumb-item>角色列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">

            <div>
                <el-button icon="el-icon-add" @click="handleAdd"  type="success">添加</el-button>
            </div>

            <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="序号" sortable width="150"></el-table-column>
                <el-table-column prop="title" label="名称" width="160" :formatter="formattitle">
                </el-table-column>
                <el-table-column prop="summary" label="描述">
                </el-table-column>

                <el-table-column label="操作" width="320" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="roleAuth(scope.row)">
                            角色权限
                        </el-button>
                        <el-button type="text" icon="el-icon-edit" @click="addSub(scope.$index, scope.row)">添加子角色</el-button>
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="block">
                <el-pagination
                        background
                        layout="prev, pager, next"
                        :total="1000">
                </el-pagination>
            </div>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="type ? '添加' : '编辑' " :visible.sync="editVisible" width="30%">
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="角色名称">
                    <el-input v-model="form.title"></el-input>
                </el-form-item>
                <el-form-item label="角色描述">
                    <el-input v-model="form.summary"></el-input>
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

        <el-dialog
                title="权限修改"
                :visible.sync="showAuth"
                width="60%">
            <el-tree
                    ref="tree"
                    :data="treeData"
                    show-checkbox
                    node-key="id"
                    :default-checked-keys="checkedValue"
                    :props="defaultProps"
            >
            </el-tree>
            <span slot="footer" class="dialog-footer">
            <el-button @click="showAuth = false">取 消</el-button>
            <el-button type="primary" @click="editAuth">确 定</el-button>
          </span>
        </el-dialog>

    </div>
</template>

<script>
    export default {
        name: 'roleList',
        components:{},
        data() {
            return {
                tableData: [],
                cur_page: 1,
                multipleSelection: [],
                editVisible: false,
                delVisible: false,
                showIcon:false,
                form: {
                    title: '',
                    summary:'',
                    id:0,
                },
                type: 1,
                id:0,
                treeData:[],
                checkedValue:[],
                defaultProps: {
                    children: 'children',
                    label: 'title'
                },
                showAuth:false,
                curRoleId:0,
                index:1,
            }
        },
        created() {
            this.getData();
        },
        activated() {
            this.form.pid = 0;
            this.getData();
        },

        methods: {
            formattitle(row, column) {
                if(row.level<1 || row.pid<=0) return row.title;
                let blank = "\xa0\xa0".repeat(row.level);
                let pre = blank+'|';
                let line = '--';
                let line2 = line.repeat(row.level);
                return pre+line2+row.title;
            },
            // 分页导航
            handleCurrentChange(val) {
                this.cur_page = val;
                this.getData();
            },
            // 获取数据
            getData() {
                this.$http_.get('auth/role/index',{pid:this.form.pid},(res)=>{
                    // console.log(res.data);
                    this.tableData = res.data;
                });
            },
            //添加
            handleAdd(){
                this.form.title = '';
                this.form.summary = '';
                this.type = 1;
                this.id = 0;
                this.editVisible = true;
            },
            //添加子权限
            addSub(index,row){
                this.form.pid = row.id;
                this.id = 0;
                this.form.level = row.level + 1;

                this.handleAdd();
            },
            //修改
            handleEdit(index, row) {
                this.type = 0;
                this.id = row.id;
                const item = this.tableData[index];
                this.form = {
                    title: item.title,
                    summary: item.summary,
                    id:this.id,
                };
                this.editVisible = true;
            },
            handleDelete(index, row) {
                this.id = row.id;
                this.type = index;
                this.index = index;
                this.delVisible = true;
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },

            // 保存编辑
            saveEdit() {
                var url = this.id ? 'auth/role/update' : 'auth/role/create';

                this.$http_.post(url,this.form,(res)=>{
                    if(res.code=='200'){
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
                this.$http_.post('auth/role/delete',{id:this.id},(res)=>{

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
            //角色权限
            roleAuth(row){
                let params = {id:row.id};
                this.curRoleId = row.id;

                this.$http_.get('auth/role/promission',params,(res)=>{
                    this.treeData = res.data.all_rule;

                    //重置
                    this.checkedValue = [];
                    //返回的 是 json 对象 需 转为数组
                    for (let i in res.data.current_role_auth){
                        this.checkedValue.push(i);
                    }

                    // console.log(this.treeData);
                    this.showAuth = true;
                })
            },
            editAuth() {
                let authData = this.$refs.tree.getCheckedNodes();

                let authIds = '';
                authData.forEach((val)=>{
                    authIds += val.id+',';
                });

                authIds = authIds.substr(0, authIds.length - 1);
                console.log(authIds);

                this.$http_.post('auth/role/promission-edit',{authIds:authIds,role_id:this.curRoleId},(res)=>{
                    // console.log(res);
                    this.showAuth = false;
                    if(res.code=='200'){
                        this.$message.success(res.message);
                    }else{
                        this.$message.warning(res.message);
                    }
                })
            }
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
