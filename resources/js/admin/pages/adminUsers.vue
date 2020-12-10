<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">사용자 계정 관리 &nbsp; <Button @click="addModal=true" ><Icon type="md-add"/>추가</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>이름</th>
								<th>Email</th>
								<th>유저 타입</th>
								<th>날짜</th>
								<th>수정 및 삭제</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user, i) in users " :key="i" v-if="users.length" >
								<td>{{user.id}}</td>
								<td class="table_name">{{user.fullName}}</td>
								<td class="">{{user.email}}</td>
								<td class="">{{user.userType}}</td>
								<td>{{user.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(user,i)" >수정</Button>
									<Button type="error" size="small" @click="showDeletingModal(user, i)" :loading="user.isDeleting"  >삭제</Button>
								</td>
							</tr>
										
						</table>
					</div>
				</div>
				  

             <!-- tag adding modal -->
				<Modal
					v-model="addModal"
					title="어드민 추가"
					:mask-closable="false"
					:closable="false"

					>
					<div class="space">
                        <Input type="text" v-model="data.fullName" placeholder="Full name"  />
                    </div>
                    <div class="space">
                        <Input type="email" v-model="data.email" placeholder="Email"  />
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password"  />
                    </div>
                    <div class="space">
                        <Select v-model="data.userType"  placeholder=" 유저타입을 선택">
                            <Option value="Admin" >어드민</Option>
                            <Option value="User" >유저</Option>
                            <!-- <Option value="Editor" >Editor</Option> -->
                        </Select>
                    </div>

					<div slot="footer">
						<Button type="default" @click="addModal=false">닫기</Button>
						<Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : '추가'}}</Button>
					</div>

				</Modal>

				<!-- tag editing modal -->
				<Modal
					v-model="editModal"
					title="어드민 수정"
					:mask-closable="false"
					:closable="false"

					>
					<div class="space">
                        <Input type="text" v-model="editData.fullName" placeholder="Full name"  />
                    </div>
                    <div class="space">
                        <Input type="email" v-model="editData.email" placeholder="Email"  />
                    </div>
                    <div class="space">
                        <Input type="password" v-model="editData.password" placeholder="Password"  />
                    </div>
                    <div class="space">
                        <Select v-model="editData.userType"  placeholder="유저 타입">
                            <Option value="Admin" >Admin</Option>
                            <Option value="User" >User</Option>
                        </Select>
                    </div>
					<div slot="footer">
						<Button type="default" @click="editModal=false">닫기</Button>
						<Button type="primary" @click="editAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : '수정'}}</Button>
					</div>

				</Modal>

				
				<!-- delete alert modal -->
				<!-- <Modal v-model="showDeleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>삭제</span>
					</p>
					<div style="text-align:center">
						<p>정말로 삭제 하실래요?</p>
						
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag" >삭제</Button>
					</div>
				</Modal> -->
				<deleteModal></deleteModal>

			</div>
		</div>
    </div>
</template>

<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";
export default {
	data(){
		return {
			data : {
				fullName: '',
				email: '',
				password: '',
				userType: 'Admin'
			}, 
			addModal : false, 
			editModal : false, 
			isAdding : false, 
			users : [],
			editData : {
				tagName:''
			},
			index : -1,
			showDeleteModal: false,
			isDeleting : false,
			deleteItem: {},
            deletingIndex: -1,
            websiteSettings: [], 
			
					
		}
	},
	methods : {
		async addAdmin(){
			if(this.data.fullName.trim()=='') return this.e('이름 필요')
                                                        //common.js의 method 에러의 e
            if(this.data.email.trim()=='') return this.e('Email 필요')
            if(this.data.password.trim()=='') return this.e('비밀번호 필요')
            if(this.data.userType.trim()=='') return this.e('유저타입 필요')
			
            //if(!this.data.role_id) return this.e('User type  is required')
			const res = await this.callApi('post', 'app/create_user', this.data)
											//web.php의 post방식
			if(res.status===201){
				this.tags.unshift(res.data)
				this.s('어드민이 성공적으로 추가!')
				this.addModal = false
				this.data.tagName = ''
			}else{
					if(res.status==422){
						for(let i in res.data.errors){
                            this.e(res.data.errors[i])
                        }
					}else{
						this.swr()
					}
					
				}
				
			
		},
		async editAdmin(){
			if(this.editData.fullName.trim()=='') return this.e('어드민 이름이 필요합니다')
                                                        //common.js의 method 에러의 e
            if(this.editData.email.trim()=='') return this.e('Email is required')
            if(this.editData.userType.trim()=='') return this.e('User type  is required')
			const res = await this.callApi('post', 'app/edit_user', this.editData)
											//web.php의 post방식
			if(res.status===200){
				this.users[this.index] = this.editData
				this.s('어드민이 성공적으로 수정!')
				this.editModal = false
				
			}else{
				if(res.status==422){
					for(let i in res.data.errors){
                        this.e(res.data.errors[i][0])
					}
					
				}else{
					this.swr()
				}
				
			}
		},
		
		showEditModal(user, index){
			let obj={
				id : user.id, 
				fullName : user.fullName, 
				email : user.email, 
				userType : user.userType, 
			}
			this.editData = obj
			this.editModal = true
			this.index = index
		},	
		async deleteTag(){
			this.isDeleting = true
			const res = await this.callApi('post','app/delete_tag', this.deleteItem)
			if(res.status===200){
				this.tags.splice(this.deletingIndex,1)
				this.s('태그가 성공적으로 삭제!')
			}else{
				this.swr()
			}
			this.isDeleting = false
				this.showDeleteModal = false
		},
		showDeletingModal(tag, i){
		const deleteModalObj  =  {
				showDeleteModal: true, 
				deleteUrl : 'app/delete_tag', 
				data : tag, 
				deletingIndex: i, 
				isDeleted : false,
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj)
			console.log('delete method called')
			// this.deleteItem = tag
			// this.deletingIndex = i
			// this.showDeleteModal = true
		}
	}, 
	
		
	
	async created(){
        const res = await this.callApi('get','app/get_users')
        if(res.status==200){
            this.users = res.data
        }else{
            this.swr()
        }			
		
	
	},
	components: {
			deleteModal
	},
	computed: {
			...mapGetters(["getDeleteModalObj"])
	},
	watch: {
			getDeleteModalObj(obj) {
			if (obj.isDeleted) {
				this.categoryLists.splice(obj.deletingIndex, 1);
			}
			}
	}
	
	
	
	
}
</script>