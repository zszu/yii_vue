<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 菜单管理</el-breadcrumb-item>
                <el-breadcrumb-item>菜单列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">
            <div>
                <el-button icon="el-icon-add" @click="handleAdd"  type="success">添加</el-button>
            </div>
            <el-table :data="tableData" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">

                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="id" label="序号" sortable width="150"></el-table-column>
                <el-table-column prop="name" label="名称" width="160" :formatter="formatname"> </el-table-column>
                <el-table-column prop="path" label="路由"></el-table-column>
                <el-table-column prop="is_menu" label="是菜单" :formatter="formatter"></el-table-column>
                <el-table-column prop="order_by" label="排序"></el-table-column>
                <el-table-column prop="icon" label="图标" >
                    <template  slot-scope="scope">
                        <i :class="scope.row.icon"></i>
                    </template>
                </el-table-column>

                <el-table-column label="操作" width="240" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="addSub(scope.$index, scope.row)">添加子菜单</el-button>
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="type ? '添加': '编辑' " :visible.sync="editVisible" width="30%">
            <el-form ref="form" :model="form" label-width="100px">

                <el-form-item label="名称">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="排序">
                    <el-input v-model="form.order_by"></el-input>
                </el-form-item>
                <el-form-item label="路由">
                    <el-input v-model="form.path"></el-input>
                </el-form-item>
                <el-form-item label="图标">
                    <i :class="form.icon"></i>
                    <span @click="showIcon=true">选择图标</span>
                </el-form-item>
                <el-form-item label="描述">
                    <el-input v-model="form.desc"></el-input>
                </el-form-item>
                <el-form-item label="是否菜单">
                    <el-radio v-model="form.is_menu" label="1">是</el-radio>
                    <el-radio v-model="form.is_menu" label="0">否</el-radio>
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
        <icon @selectIcon="selectIcon" v-show="showIcon"></icon>
    </div>
</template>

<script>
    import icon from '@/pages/common/icon';

    export default {
        name: 'basetable',
        components:{icon},
        data() {
            return {
                // url: './static/vuetable.json',
                tableData: [],
                cur_page: 1,
                multipleSelection: [],
                editVisible: false,
                delVisible: false,
                showIcon:false,
                form: {
                    name: '',
                    desc:'',
                    path: '',
                    is_menu:'1',
                    level:1,
                    pid:0,
                    id:0,
                    icon:'',
                },
                type: 1,
                id:0,
                level:-1,
                index:0,
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
            // 分页导航
            handleCurrentChange(val) {
                this.cur_page = val;
                this.getData();
            },
            // 获取 数据
            getData() {
                // console.log(this.form.pid);return false;
                this.$http_.get('menu/index',{pid:0},(res)=>{
                    console.log(res);
                    this.tableData = res.data;
                });
            },
            formatter(row, column) {
                // console.log(row);
                return row.is_menu=='1'?'是':'否';
            },
            formatname(row, column) {
                if(row.level<1 || row.pid<=0) return row.name;
                let blank = "\xa0\xa0".repeat(row.level);
                let pre = blank+'|';
                let line = '-';
                let line2 = line.repeat(row.level);
                return pre+line2+row.name;
            },
            formaticon(row, column) {
                return '<i class="'+row.icon+'"></i>';
            },
            //添加
            handleAdd(){
                this.form.name = '';
                this.form.path = '';
                this.form.desc = '';
                this.id = 0;
                this.editVisible = true;
            },
            //修改
            handleEdit(index, row) {

                this.type = 0;
                this.id = row.id;
                const item = this.tableData[index];
                this.form = {
                    name: item.name,
                    path: item.path,
                    desc: item.desc,
                    is_menu:item.is_menu,
                    order_by:item.order_by,
                    id:this.id,
                    pid:row.pid,
                    icon:item.icon,
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
                var url = this.id  ? 'menu/update' : 'menu/create';

                this.$http_.post(url , this.form,(res)=>{
                    if(res.code== 200){
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
                this.$http_.post('menu/delete',{id:this.id},(res)=>{
                    // console.log(res);
                    if(res.code=='200'){
                        this.tableData.splice(this.index, 1);
                        this.$message.success(res.message);
                    }else{
                        this.$message.warning(res.message);
                    }
                });
                this.delVisible = false;
                // this.tableData.splice(this.idx, 1);
            },
            //添加子菜单
            addSub(index,row){
                this.form.pid = row.id;
                this.id = 0;
                this.level = row.level + 1;
                this.handleAdd();
            },
            //选择图标
            selectIcon(icon) {
                // console.log(icon);
                this.showIcon = false;
                this.form.icon = icon;
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
