<?php
  if(get_current_user_id()==0){
?>
     <a class="custom-chikotw" href="https://si.chikotw.com/account/login/?redirect=https%3A%2F%2Fsi.chikotw.com%2Fselect-plan%2F">
          <span style="box-shadow: rgba(142, 194, 31, 0.35) 0px 5px 21px;background-color: #8ec21f;border: none;border-radius: 9999px;font-weight: 500;color: #fff;cursor: pointer;font-size: 1.125rem;padding: 0.75rem 1.25rem;">登入以購買會員</span>
    </a>
<?php
  }
  else{
?>
<div class="hp-membership-plans hp-grid hp-block">
    <div class="hp-row">
      <div class="hp-grid__item hp-col-sm-6 hp-col-xs-12">
        <div class="hp-membership-plan hp-membership-plan--view-block">
          <header class="hp-membership-plan__header">
            <h3 class="hp-membership-plan__name">贊助會員</h3>
            <div class="hp-membership-plan__price">
              <span class="woocommerce-Price-amount amount"></span>
              <bdi>
                <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">NT$</span>65.00</span>
              </bdi>
            </div>
          </header>
          <div class="hp-membership-plan__content">
            <div class="hp-membership-plan__description">
              <p><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">您的贊助金，我們將用來支付伺服器跟技術權利金的部分。</span></span></span></span></span></span></p>
              <p><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">為了感謝您的幫助，我們幫您準備了：</span></span></span></span></span></span></p>
              <ol>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">上傳送審海報的功能</span></span></span></span></span></span></li>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">線上預約檔期的功能</span></span></span></span></span></span></li>
                <li>您還可以直接留言給社區的承辦人</li>
              </ol>
            </div>
          </div>
          <footer class="hp-membership-plan__footer">
            <button class="hp-membership-plan__select-button button button--primary alt" data-component="link" data-url="https://si.chikotw.com/select-plan/415/" style="box-shadow: rgba(142, 194, 31, 0.35) 0px 5px 21px;" type="button"><span>Buy Plan</span></button>
          </footer>
        </div>
      </div>
      <div class="hp-grid__item hp-col-sm-6 hp-col-xs-12">
        <div class="hp-membership-plan hp-membership-plan--view-block">
          <header class="hp-membership-plan__header">
            <h3 class="hp-membership-plan__name">Vip會員</h3>
            <div class="hp-membership-plan__price">
              <span class="woocommerce-Price-amount amount"></span>
              <bdi>
                <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">NT$</span>360.00</span>
              </bdi>
            </div>
          </header>
          <div class="hp-membership-plan__content">
            <div class="hp-membership-plan__description">
              <p><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">您的贊助金，我們將用來支付日常營運跟Google的費用。</span></span></span></span></span></span></span></p>
              <p><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">為了感謝您的幫助，我們幫您準備了：</span></span></span></span></span></span></span></p>
              <ol>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">上傳送審海報的功能</span></span></span></span></span></span></span></li>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">線上預約檔期的功能</span></span></span></span></span></span></span></li>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">您還可以直接留言給社區的承辦人</span></span></span></span></span></span></li>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">您每次的社區廣告費用將折抵10%</span></span></span></span></span></span></li>
                <li><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit"><span style="vertical-align: inherit">更多的特殊功能將陸續上線</span></span></span></span></span></span></li>
              </ol>
            </div>
          </div>
          <footer class="hp-membership-plan__footer">
            <button class="hp-membership-plan__select-button button button--primary alt" data-component="link" data-url="https://si.chikotw.com/select-plan/418/" style="box-shadow: rgba(142, 194, 31, 0.35) 0px 5px 21px;" type="button"><span>Buy Plan</span></button>
          </footer>
        </div>
      </div>
    </div>
  </div>
<?php
  } 
?>