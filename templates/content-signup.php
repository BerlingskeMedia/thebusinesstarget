

<script>
<?php
  $glid = get_field('global_location_id', 'option');
  $lid = get_field('location_id');
  $nlid = get_field('newsletter_id', 'option');
  $mid = get_field('media_id', 'option');
  $show_bem_permission = get_field('show_bem_permission', 'option');
  $show_bem_permission_value = $show_bem_permission ? 'true' : 'false';
  if (!$lid) {
    $lid = $glid;
  }
?>
  var businesstarget_setup = {};
  businesstarget_setup.mid = <?php echo '"' . $mid . '"' ?>;
  businesstarget_setup.nlid = <?php echo '"' . $nlid . '"' ?>;
  businesstarget_setup.lid = <?php echo '"' . $lid . '"' ?>;
</script>

<div class="signup" ng-app="businesstarget">
  <div class="col-sm-12">
    <div class="col-md-8 col-md-offset-2 signup-container">
      <div ng-view></div>
    </div>
    <div class="col-sm-8 col-sm-offset-2">
      <p class="login white">Allerede tilmeldt? <a href="#login">Opdater din profil her</a></p>
    </div>
  </div>


<script type="text/ng-template" id="step1.html">
<?php the_field('step1_header', 'option');?>
<form name="formstep1">
  <div class="row">
    <div class="col-sm-6">
      <input type="text" name="navn" ng-model="user.fornavn" ng-required="true" placeholder="Fornavn"/>
      <input type="text" name="navn" ng-model="user.efternavn" ng-required="true" placeholder="Efternavn"/>
      <input type="email" name="mail" ng-model="user.email" ng-required="true" placeholder="E-mail"/>
    </div>
    <div class="col-sm-6">
    <input type="number" name="zip" ng-required="true" ng-model="user.postnummer_dk" min="999" max="9999" placeholder="Postnummer"/>
    <select name="stilling" ng-required="true" ng-model="user.occupation">
        <option disabled selected value="">Vælg stilling</option>
        <option ng-repeat="i in interests | filter: {interesse_parent_id: 343}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
    </select>
    <select name="branche" ng-required="true" class="select2" ng-model="user.industry" value="Vælg branche">
        <option disabled selected value="">Vælg branche</option>
        <option ng-repeat="i in interests | filter: {interesse_parent_id: 310}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
    </select>
  </div>
  <div class="col-sm-12 terms">
    <input type="checkbox" ng-required="true" ng-model="accepted" name="terms" />
    <div class="terms-text" ng-click="displayTerms = true">Jeg accepterer <span class="terms-emph">betingelserne</span>
    </div>
    <div class="conditions" ng-class="{visible: displayTerms || accepted}"><?php the_field('terms', 'option');?></div>
  </div>
  <div class="col-sm-12 terms" ng-if="<?php echo $show_bem_permission_value ?>">
    <input type="checkbox" ng-model="user.bem_permission" name="terms" />
    <div class="terms-text" ng-click="displayBemTerms = true"><?php the_field('bem_signup_teaser', 'option');?></div>
    <div class="conditions" ng-class="{visible: displayBemTerms || user.bem_permission}"><?php the_field('bem_terms', 'option');?></div>
  </div>
  <div class="col-sm-12 signup-submit">
    <input type="submit" value="Tilmeld" class="button col-md-4 col-md-offset-8" ng-click="submit_step1(user)"/>
  </div>
</div>
</form>
<div class="stage one"></div>
</script>
<script type="text/ng-template" id="step2.html">
  <?php the_field('step2_header', 'option');?>
  <form name="formstep2">
    <div class="row">
      <div class="col-sm-6">
        <select name="employees" ng-required="true" ng-model="user.employees">
            <option disabled selected value="">Antal ansatte</option>
            <option ng-repeat="i in interests | filter: {interesse_parent_id: 335} | orderBy: 'interesse_id'" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
        </select>
        <select name="managementlevel" ng-required="true" ng-model="user.managementlevel">
            <option disabled selected value="">Ledelsesniveau</option>
            <option ng-repeat="i in interests | filter: {interesse_parent_id: 391} | orderBy: 'interesse_id'" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
        </select>
        <select name="buyer" ng-required="true" ng-model="user.buyer">
            <option disabled selected value="">Involveret i køb</option>
            <option ng-repeat="i in interests | filter: {interesse_parent_id: 400}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
        </select>
      </div>
      <div class="col-sm-6">
      <select name="car" ng-required="true" ng-model="user.car">
          <option disabled selected value="">Firmabil</option>
          <option ng-repeat="i in interests | filter: {interesse_parent_id: 397}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
      <select name="traveller" ng-required="true" ng-model="user.traveller">
          <option disabled selected value="">Forretningsrejsende</option>
          <option ng-repeat="i in interests | filter: {interesse_parent_id: 403}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
      <button class="button step-two col-sm-6 col-sm-offset-6 col-xs-12" ng-click="submit_step2(user)">Vælg interesser</button>
      </div>
    </div>
  </form>

  <div class="stage two"></div>
</script>
<script type="text/ng-template" id="step3.html">
  <?php the_field('step3_header', 'option');?>

  <form name="formstep3">
    <div class="row">
      {{businessinterests}}
      <div class="bi choice col-lg-4 col-sm-6" ng-repeat="i in interests | filter: {interesse_parent_id: 407}">
        <input type="checkbox" checklist-model="businessinterests" checklist-value="i.interesse_id" /><label>{{i.interesse_navn}}</label>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <input type="submit" class="col-sm-4 col-sm-offset-4 submit" ng-click="submit_step3(businessinterests)" value="Gem"/>
      </div>
    </div>
  </form>
  <div class="stage three"></div>
</script>
<script type="text/ng-template" id="thanks.html">
  <div class="view thanks">
    <?php the_field('thanks', 'option');?>
  </div>
</script>
<script type="text/ng-template" id="edit.html">
<form name="edit">
  <div class="row">
    <div class="col-sm-6">

    <input type="text" name="navn" ng-model="user.fornavn" ng-required="true" placeholder="Fornavn"/>
    <input type="text" name="navn" ng-model="user.efternavn" ng-required="true" placeholder="Efternavn"/>
    <input type="email" name="mail" disabled ng-model="user.email" placeholder="E-mail"/>
    </div>
    <div class="col-sm-6">
    <input type="number" name="zip" ng-required="true" ng-model="user.postnummer_dk" min="999" max="9999" placeholder="Postnummer"/>
    <select name="stilling" ng-required="true" ng-model="user.occupation">
        <option disabled value="">Vælg stilling</option>
        <option ng-selected="user.occupation == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 343}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
    </select>

    <select name="branche" ng-required="true" class="select2" ng-model="user.industry" value="Vælg branche">
        <option disabled value="">Vælg branche</option>
        <option ng-selected="user.industry == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 310}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
    </select>
    </div>
  </div>
  <div class="divider"></div>
  <div class="row">
    <div class="col-sm-6">
      <select name="employees" ng-required="true" ng-model="user.employees">
          <option disabled value="">Antal ansatte</option>
          <option <option ng-selected="user.employees == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 335} | orderBy: 'interesse_id'" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
      <select name="managementlevel" ng-required="true" ng-model="user.managementlevel">
          <option disabled value="">Ledelsesniveau</option>
          <option ng-selected="user.managementlevel == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 391} | orderBy: 'interesse_id'" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
      <select name="buyer" ng-required="true" ng-model="user.buyer">
          <option disabled value="">Involveret i køb</option>
          <option ng-selected="user.buyer == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 400}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
    </div>
    <div class="col-sm-6">
      <select name="car" ng-required="true" ng-model="user.car">
          <option disabled value="">Firmabil</option>
          <option ng-selected="user.car == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 397}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
      <select name="traveller" ng-required="true" ng-model="user.traveller">
          <option disabled value="">Forretningsrejsende</option>
          <option ng-selected="user.traveller == i.interesse_id" ng-repeat="i in interests | filter: {interesse_parent_id: 403}" value="{{i.interesse_id}}">{{i.interesse_navn}}</option>
      </select>
    </div>
  </div>
  <div class="divider"></div>
  <div class="row">

    <div class="bi choice col-lg-6 col-sm-6" ng-repeat="i in interests | filter: {interesse_parent_id: 407}">
      <input type="checkbox" checklist-model="user.businessinterests" checklist-value="i.interesse_id" /><label>{{i.interesse_navn}}</label>
    </div>
  </div>
  <div class="row">
    <input type="submit" class="col-sm-4 col-sm-offset-8 submit" ng-click="submit_edit(user)" value="Gem"/>
  </div>

</form>

</script>
<script type="text/ng-template" id="login.html">
  <h2>Adgang til min profil</h2>
  <p>Sæt din e-mail ind og vi sender dig et link til din profil</p>
  <form name="loginform">
    <div ng-hide="displayThanks">
      <input type="email" placeholder="E-mail" name="mail" ng-model="mail" ng-required="true"/>
      <input type="submit" name="Submit" class="submit" value="Tilsend login" ng-click="submit_email(mail)"/>
    </div>
    <div ng-show="displayThanks">
    <?php the_field('login_text', 'option'); ?>
    <a href="#step1">Tilbage</a>
    </div>

  </form>
</script>

<script type="text/ng-template" id="unsubscribe.html">
  <h2>Afmeld</h2>
  <form>
    <div ng-hide="displayThanks">
      <input type="email" placeholder="E-mail" name="mail" value="{{email}}" ng-required="true"/>
      <input type="submit" ng-show="email" name="Submit" class="submit" value="Afmeld" ng-click="submit_unsubscribe()"/>
    </div>

    <div ng-show="displayThanks">
      <?php the_field('unsubscribe_text', 'option'); ?>
      <?php the_field('questionnaire', 'option'); ?>
      <a href="#step1">Tilbage</a>
    </div>

  </form>
</script>
</div>
