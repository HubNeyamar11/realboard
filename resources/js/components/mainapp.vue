<template>
    <div>
      <div>
      <!--========== ADMIN SIDE MENU one ========-->
      <div class="_1side_menu" >
        <div class="_1side_menu_logo">
          <h3 style="text-align:center;">Neyamar's blog</h3>
          <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
        </div>

        <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
        <div class="_1side_menu_content">
          <div class="_1side_menu_img_name">
            <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
            <p class="_1side_menu_name">Menu</p>
          </div>

          <!--~~~ MENU LIST ~~~~~~-->
          <div class="_1side_menu_list">
            <ul class="_1side_menu_list_ul">
              <!-- <li v-for="(menuItem, i) in permission" :key="i" >
                <router-link :to="'/'+menuItem.name"><Icon type="ios-speedometer" /> {{menuItem.resourceName}}</router-link>
              </li> -->


              <!-- <li><router-link to="tags"><Icon type="ios-speedometer" /> Tags</router-link></li>
              <li><router-link to="category"><Icon type="ios-speedometer" /> Category</router-link></li>
              <li><router-link to="adminusers"><Icon type="ios-speedometer" /> Admin users</router-link></li>
              <li><router-link to="role"><Icon type="ios-speedometer" /> Role Management</router-link></li>
              <li><router-link to="assignRole"><Icon type="ios-speedometer" /> Assign role</router-link></li> -->

              
              <li><router-link to="/"><Icon type="ios-home" /> 메인</router-link></li>
              <li><router-link to="/board"><Icon type="ios-book" /> 댓글</router-link></li>
              <li><router-link to="/mainboard"><Icon type="ios-paper" /> 공지사항</router-link></li>            
          
              <li><router-link to="/admin"><Icon type="ios-person" /> 사용자 계정 관리</router-link></li>       
            </ul>
          </div>
        </div>
      </div>
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <div class="header">
        <div class="_2menu _box_shadow">
          <div class="_2menu_logo">
            <ul class="open_button">
              <li>
                <Icon type="ios-login" />
                로그인
              </li>
              <!--<li><Icon type="ios-albums" /></li>-->
            </ul>
          </div>
        </div>
      </div>
      <!--========= HEADER ==========-->
    </div>
    	<router-view/>
    </div>
</template>