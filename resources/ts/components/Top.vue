<template>
  <div class="bg">
    <v-container class="container">
      <!-- 格言 -->
      <v-row class="mt-8">
        <v-col cols="12">
          <h2>Maxim</h2>
          <div class="maxim">
            <h1 style="text-align: center;">{{ maxim.maxim }}</h1>
            <p class="mb-0" style="text-align: right;">{{ maxim.author }}</p>
          </div>
        </v-col>
      </v-row>
      <!-- ニュース -->
      <v-row justify="center" class="mt-10">
        <v-col cols="12">
          <h2>News</h2>
          <hr class="mb-1">
          <v-row class="mt-3 mx-2">
            <v-col clos="5">
              <v-select
                dark
                :items="NEWS_CATEGORES"
                v-model="newsCategory"
                label="カテゴリー検索"
                @change="getNews"
              ></v-select>
            </v-col>
            <v-col clos="7">
              <v-text-field
                dark
                clearable
                v-model="newsWordSerch"
                label="キーワード検索"
                @change="getNews"
              ></v-text-field>
            </v-col>
          </v-row>
          <div v-if="!newsFalseFlag">
            <v-row justify="center" class="mt-3">
              <v-col
                class="my-1"
                cols="6"
                v-for="(news,index) in displayNewses"
                :key=index>
                <a :href=news.url target=”_blank” style="text-decoration: none;">
                <v-tooltip bottom color="#1E1E1E">
                  <template v-slot:activator="{ on, attrs }">
                    <v-sheet
                      dark
                      v-on="on"
                      v-bind="attrs"
                      max-height="150"
                      class="px-1">
                      <v-row>
                        <v-col cols="5">
                          <v-img
                            max-height="150"
                            :aspect-ratio="16/9"
                            :src="news.urlToImage">
                            <template v-slot:placeholder>
                              <v-row
                                class="fill-height ma-0"
                                align="center"
                                justify="center">
                                <v-progress-circular
                                  indeterminate
                                  color="grey lighten-5"
                                ></v-progress-circular>
                              </v-row>
                            </template>
                          </v-img>
                        </v-col>
                        <v-col cols="7" >
                          <h3 class="lineClamp">{{ news.title }}</h3>
                          <p style="text-align: right;">{{ newsPublishedAt(news.publishedAt) }}</p>
                        </v-col>
                      </v-row>
                    </v-sheet>
                  </template>
                <span style="color: white;">News詳細へ遷移</span>
                </v-tooltip>
                </a>
              </v-col>
            </v-row>
            <v-pagination
              dark
              class="my-5"
              color="#1B5E20"
              v-model="newsPage"
              :length="newsLength"
              :total-visible="8"
              circle
              @input="chengePage"
            ></v-pagination>
          </div>
        <h2 v-else-if="newsFalseFlag" style="text-align: center;" class="my-4">ニュースの取得に失敗しました</h2>
        <h2 v-if="!newses.length" style="text-align: center;" class="my-4">ニュースがありません</h2>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts">
import {Component, Mixins, Emit} from 'vue-property-decorator';
import Const from '../common/const';
import Axios from 'axios';
import Util from '../common/util';

@Component ({
  name: 'TOP'
})

export default class Top extends Mixins(Const, Util){
  // 取得ニュース
  private newses: string[] = [];
  // 表示表ニュース
  private displayNewses: string[] = [];
  // ニュース取得失敗フラグ
  private newsFalseFlag: boolean = false;
  // ニュースページネーション開始位置
  private newsPage: number = 1;
  // ニュース表示最大数
  private newsPageSize: number = 6;
  // ニュースカテゴリ選択
  private newsCategory: string = '';
  // ニュースカテゴリ選択
  private newsWordSerch: string = '';
  // 格言
  private maxim: string = '';
  // ニュースページネーション最大数計算
  get newsLength(): number {
    return Math.ceil(this.newses.length / this.newsPageSize);
  }
  // ニュース公表時間表示用
  get newsPublishedAt(): any {
    return(publishedAt: string) => {
      return `${publishedAt.slice(0,10)} ${publishedAt.slice(11,16)}`;
    }
  }

  mounted() {
    this.getNews();
    this.getMaxim();
  }
  /**
   * ニュース取得
   */
  private getNews(): void {
    this.setLoading();
    Axios.post(`/api/top/getNews`,{
      newsCategory: this.newsCategory,
      newsWordSerch: this.newsWordSerch,
    }).then((res) => {
      res.data.status === 'error' ? this.newsFalseFlag = true : this.newsFalseFlag = false;
      this.newses = res.data.articles;
      this.displayNewses = this.newses.slice(0,this.newsPageSize);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * 格言取得
   */
  private getMaxim(): void {
    this.setLoading();
    Axios.get(`/api/top/getMaxim`).then((res) => {
      this.maxim = res.data.maxim;
      console.log(res.data.maxim);
    }).catch((e) => {
      this.authCheck(e);
      this.serverError(e);
    }).finally(() => this.closeLoading());
  }
  /**
   * ニュースページネーション押下
   */
  private chengePage(num: number): void {
    this.displayNewses = this.newses.slice(this.newsPageSize *(num -1), this.newsPageSize*(num));
  }
}
</script>
<style scoped>

.container {
  padding: 4% 5%;
}
.lineClamp {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  overflow: hidden;
  text-align: left;
}
.maxim {
  color: black;
  padding: 1rem 2rem;
  border-top: 4px solid #d8dcdc;
  border-right: 4px solid #666;
  border-bottom: 4px solid #333;
  border-left: 4px solid #868888;
  border-radius: 0;
  background-image: -webkit-linear-gradient(135deg, #ccc 0%, #868888 20%, #d8dcdc 34%, white 53%, #ccc 100%);
  background-image: linear-gradient(-45deg, #ccc 0%, #868888 20%, #d8dcdc 34%, white 53%, #ccc 100%);
}
</style>
