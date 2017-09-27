$(document).bind("contextmenu",function(e){
preventDefault(e);
});
function avoidKeyPressing(event){
if(event.ctrlKey){
alert('Open in new Tab is diasbled.');
if(event.preventDefault)
event.preventDefault();
return false;
}else if(event.shiftKey){
alert('Open in new Window is diasbled.');
if(event.preventDefault)
event.preventDefault();
return false;
}else if(typeof(event.altKey)=='undefined'?event.originalEvent.altKey:event.altKey){
alert('Saving this link is diasbled.');
if(event.preventDefault)
event.preventDefault();
return false;
}
return true;
}
function preventDefault(e) {
e = e || window.event;
if (e.preventDefault)
e.preventDefault();
e.returnValue = false;
}
function theMouseWheel(e) {
preventDefault(e);
}
function disable_scroll() {
if (window.addEventListener) {
window.addEventListener('DOMMouseScroll', theMouseWheel, false);
}
window.onmousewheel = document.onmousewheel = theMouseWheel;
}
$(document).keydown(function(e) {
// var code = (e.keyCode ? e.keyCode : e.which);
// if(code == 8 || code == 116){
// return false;
// }
});
/***************Disable right click************************/
function clickIE() {
if (document.all) {
return false;
}
}
function clickNS(e) {
if(document.layers||(document.getElementById&&!document.all)) {
if (e.which==2||e.which==3) {
return false;
}
}
}
if (document.layers){
document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;
}
else{
document.onmouseup=clickNS;document.oncontextmenu=clickIE;
}
/******************common functions *****************************/
function readXML(filePath,func){
var xml;
$.ajax({
type: "POST",
url: filePath,
async:false,
dataType: ($.browser.msie) ? "text" : "xml",
success: function(data) {
if (typeof data == "string") {
xml = new ActiveXObject("Microsoft.XMLDOM");
xml.async = false;
xml.loadXML(data);
} else {
xml = data;
}
eval(func);
},
error : function(){
window.location.href="error.html?E404";
}
});
}
function checkVersion(){
if($.browser.msie){
if($.browser.version<7){
window.location.href="error.html?E505";
}
}else if($.browser.mozilla){
if($.browser.version<4){
window.location.href="error.html?E505";
}
}else if($.browser.webkit) {
if($.browser.version<15){
window.location.href="error.html?E505";
}
}else if($.browser.safari) {
if($.browser.version<5.1){
window.location.href="error.html?E505";
}
}
}
function readAndReturnXML(filePath){
var xml;
$.ajax({
type: "POST",
url: filePath,
async : false,
dataType: ($.browser.msie) ? "text" : "xml",
success: function(data) {
if (typeof data == "string") {
xml = new ActiveXObject("Microsoft.XMLDOM");
xml.async = false;
xml.loadXML(data);
} else {
xml = data;
}
},
error : function(){
window.location.href="error.html?E404";
}
});
return xml;
}
function alignHeight(){
var height = $(window).height()-($("#header").height()+$("#footer").height());
$('#mainleft').css({"height":height});
$('#mainright').css({"height":height});
}
function quizPageHeight(){
var height = $(window).height()-($("#header").height()+$("#footer").height());
$('#mainleft').css({"height":height});
$('#mainright').css({"height":height});
if(iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues].quesType=="TYPING"){
$('#questionCont').height($('#mainleft').height()-$('#groups').height());
$('#currentQues').height($('#questionCont').height()-1);
} else{
$('#questionCont').height($('#mainleft').height()-($('#groups').height()+$('#sectionsField').height()));
$('#currentQues').height('90%');
}
if($('#groups').height()!=0){
$('#timer').height($('#groups').height()+$('#sectionsField').height());
}
$('.numberpanel').height($('#mainright').height()-$('#timer').height());
$('#numberpanelQues').height($('.numberpanel').height()-($('#viewSection').height()+$('#quesPallet').height()+$('#legend').height()+30));
$('#sectionSummaryDiv').height($('#questionCont').height());
$('#QPDiv').height($('#questionCont').height());
$('#profileDiv').height($('#questionCont').height());
$('#instructionsDiv').height($('#questionCont').height());
$('#typingInstDiv').height($('.numberpanel').height()-($('#viewSection').height()+$('#typingSubmit').height()+30));
}
function parseSysInstructions(page,sysInstrXML,useSystemInstructions,orgId,mockId,isOptionalSectionsAvailable,isMarkedForReviewConsidered,compMockDuration){
var o,instructionContent;
var xml = readAndReturnXML(orgId+'/'+mockId+'/custInstructions.xml');
$('#defaultLang').val($(xml).find('LANGID').text());
o = new Option("-- Select --", "0");
$(o).html("-- Select --");
$("#defaultLanguage").append(o);
$(xml).find("INSTRUCTION").each(function(){
var langName = $(this).find("LANGNAME").text();
var langId = $(this).find("LANGID").text();
o = new Option(langName, "cusInstText"+langId);
$(o).html(langName);
$("#cusInst").append(o);
o = new Option(langName, "sysInstText"+langId);
$(o).html(langName);
$("#basInst").append(o);
o = new Option(langName, langId);
$(o).html(langName);
$("#defaultLanguage").append(o);
if($.trim($(this).find("INSTRUCTIONTEXT").text())=="" || $.trim($(this).find("INSTRUCTIONTEXT").text()) == null || $.trim($(this).find("INSTRUCTIONTEXT").text())== " ")
instructionContent = "The instructions are not available in the chosen language. ";
else{
instructionContent = $.trim($(this).find("INSTRUCTIONTEXT").text());
}
if(page=='inst'){
$('#secondPagep1').append("<div id='cusInstText"+langId+"' style='display:none;height:93%;width:99.5%;overflow:auto'>"+instructionContent+"</div>");
$('#firstPage').append("<div id='sysInstText"+langId+"' style='display:none;height:93%;width:99.5%;overflow:auto'>The instructions are not available in the chosen language.</div>");
}else{
$('#secondPagep1').append("<div id='cusInstText"+langId+"' style='display:none;'>"+instructionContent+"</div>");
$('#firstPage').append("<div id='sysInstText"+langId+"' style='display:none;'>The instructions are not available in the chosen language.</div>");
}
$(sysInstrXML).find("INSTRUCTION").each(function(){
if(langName.toUpperCase() == $(this).find("LANGNAME").text().toUpperCase()){
if($.trim($(this).find("INSTRUCTIONTEXT").text())=="" || $.trim($(this).find("INSTRUCTIONTEXT").text()) == null ||
$.trim($(this).find("INSTRUCTIONTEXT").text())== " " || $.trim($(this).find("INSTRUCTIONTEXT").text())== "<br>" || $.trim($(this).find("INSTRUCTIONTEXT").text()) == "<br/>"){
instructionContent = "The instructions are not available in the chosen language. ";
}
else{
instructionContent = $.trim($(this).find("INSTRUCTIONTEXT").text());
}
$("#sysInstText"+langId).html(instructionContent);
if(useSystemInstructions=="YES"){
if(langName.toUpperCase() == "ENGLISH"){
if(isMarkedForReviewConsidered == "NO"){
$(".considerMarkedReview").html("NOT");
}else if(isMarkedForReviewConsidered == "YES"){
$(".considerMarkedReview2").html("or marked for review");
}
if(isOptionalSectionsAvailable == "YES"){
$("#sysInstText"+langId).append($(sysInstrXML).find("OPTIONALTEXTENGLISH").text());
}
}else if(langName.toUpperCase() == "HINDI"){
if(isMarkedForReviewConsidered == "NO"){
$(".considerMarkedReviewHindi").html("&#2344;&#2361;&#2368;&#2306;");
}else if(isMarkedForReviewConsidered == "YES"){
$(".considerMarkedReviewHindi2").html("&#2351;&#2366; &#2346;&#2369;&#2344;&#2352;&#2381;&#2357;&#2367;&#2330;&#2366;&#2352; &#2325;&#2375; &#2354;&#2367;&#2319; &#2330;&#2367;&#2344;&#2381;&#2361;&#2367;&#2340; &#2361;&#2376;");
}
if(isOptionalSectionsAvailable == "YES"){
$("#sysInstText"+langId).append($(sysInstrXML).find("OPTIONALTEXTHINDI").text());
}
}
}
}
});
});
$(".completeDuration").html(compMockDuration);
calcTotalQues(orgId,mockId);
}
function changeSysInst(param,value){
$('*[id^="'+value+'"]').hide();
$('#'+param).show();
}
function validateExpiry(orgId,mockId){
var xml = readAndReturnXML(orgId+'/'+mockId+'/confDetails.xml');
var curDate = new Date();
if($.trim($(xml).find("STARTDATE").text())!=null && $.trim($(xml).find("STARTDATE").text()) != ""){
var startDate = new Date(parseInt($.trim($(xml).find("STARTDATE").text())));
if(curDate <= startDate){
window.location.href="error.html?E105";
}
}
if($.trim($(xml).find("ENDDATE").text())!=null && $.trim($(xml).find("ENDDATE").text()) != ""){
var startDate = new Date(parseInt($.trim($(xml).find("ENDDATE").text())));
if(curDate >= startDate){
window.location.href="error.html?E105";
}
}
}
/******************index page *****************************/
function validateIndexPageURL(){
var url = document.URL;
var params = url.split("index.html?");
var orgId = $.trim(params[1]).split("@@")[0];
var mockId = $.trim(params[1]).split("@@")[1];
if(params.length>1 ){
if(mockId.indexOf("#")>-1){
mockId = mockId.substring(0,mockId.indexOf("#"));
}
if($.trim(params[1]).length>0){
validateExpiry(orgId,mockId);
var xml = readAndReturnXML(orgId+'/'+mockId+'/confDetails.xml');
basicDetails(xml);
$("#pWait").hide();
}
}else{
window.location.href="error.html";
}
}
function basicDetails(xml){
$("#mockName").html($(xml).find("MOCKNAME").text());
if($(xml).find("USELOGINID").text().toLowerCase() == "no"){
$("#loginName").prepend($(xml).find("LOGINID").text());
}else{
$("#loginName").prepend("Roll No");
}
if($(xml).find("USEDEFAULTCANDIDATEIMG").length>0 && $(xml).find("USEDEFAULTCANDIDATEIMG").text() == "NO"){
$("#candidateImg").attr("src",$(xml).find("CANDIDATEIMGPATH").text());
}else{
$("#candidateImg").attr("src","images/NewCandidateImage.jpg");
}
if($(xml).find("USEBANNER").text().toLowerCase() == "yes"){
$("#bannerImage").html("<img height= '45px' src='"+$(xml).find("BANNERPATH").text()+"'/>");
}else{
$("#bannerImage").html('<div style="margin-top:10px"><font size=4 color="#ffffff"><b>'+$(xml).find("BANNERTEXT").text()+'</b></font></div>');
$("#bannerImage").attr("align","center");
}
if($(xml).find('SHOWVIEWQPBUTTON').length>0 && $(xml).find('SHOWVIEWQPBUTTON').text() == 'NO'){
$('#viewQPTD').hide();
$('#submitTD').attr('colspan',2);
}else{
$('#viewQPTD').show();
$('#submitTD').removeAttr('colspan');
}
if($(xml).find('USEHORIZONTALOPTION').length>0 && $(xml).find('USEHORIZONTALOPTION').text() == 'YES'){
mockVar.showOptionsHorizontally = 1;
}
if($(xml).find('SHOWCALCULATOR').length>0 && $(xml).find('SHOWCALCULATOR').text() == 'YES'){
mockVar.showCalculator = 1;
$('#showCalc').show();
}
$("#footer").html("<div style='width:100%;padding-top:15px;'><center><font color='white'> Version : " +$(xml).find("VERSION").text()+"</font></center></div>");
}
/******************instrutions page**************************************/
function setInstruHeights(){
alignHeight();
var height = $('#mainleft').actual('height')-($('#instPagination'). actual('height'));
$('#firstPage').css({"height":height-10});
height = $('#mainleft').actual('height')-($('#secondPagep2').actual('height')+$('#instPagination'). actual('height'));
$('#secondPagep1').css({"height":height-10});
}
function validateInstPageUrl(){
var url = document.URL;
var params = url.split("instructions.html?");
var orgId = $.trim(params[1]).split("@@")[0];
var mockId = $.trim(params[1]).split("@@")[1];
if(mockId != null && mockId.length>0){
if(mockId.indexOf("#")>-1){
mockId = mockId.substring(0,mockId.indexOf("#"));
}
if(params.length>1 && $.trim(params[1]).length>0){
validateExpiry(orgId,mockId);
readXML(orgId+'/'+mockId+'/confDetails.xml','readSysInstructionsXMLInstPage(xml,"'+orgId+'","'+mockId+'")');
}
else{
window.location.href="error.html";
}
}else{
window.location.href="error.html";
}
}
function readSysInstructionsXMLInstPage(xml,orgId,mockId){
var langId = 1;
if($(xml).find("USEDEFAULTCANDIDATEIMG").length>0 && $(xml).find("USEDEFAULTCANDIDATEIMG").text() == "NO"){
$("#candidateImg").attr("src",$(xml).find("CANDIDATEIMGPATH").text());
}else{
$("#candidateImg").attr("src","images/NewCandidateImage.jpg");
}
var useSystemInstructions = $(xml).find("USESYSTEMINSTRUCTIONS").text();
var compMockTime = $(xml).find("COMPLETEMOCKDURATION").text();
var isOptionalSectionsAvailable = $(xml).find("ISOPTIONALSECTIONSAVAILABLE").text();
var isMarkedForReviewConsidered = $(xml).find("ISMARKEDFORREVIEWCONSIDERED").text();
if($(xml).find("USEBANNER").text().toLowerCase() == "yes"){
$("#bannerImage").html("<img height= '45px' src='"+$(xml).find("BANNERPATH").text()+"'/>");
}else{
$("#bannerImage").html('<div style="margin-top:10px"><font size=4 color="#ffffff"><b>'+$(xml).find("BANNERTEXT").text()+'</b></font></div>');
$("#bannerImage").attr("align","center");
}
$("#footer").html("<div style='width:100%;padding-top:15px;'><center><font color='white'> Version : " +$(xml).find("VERSION").text()+"</font></center></div>");
if(useSystemInstructions.toUpperCase()=="NO"){
var xml =readAndReturnXML(orgId+'/'+mockId+'/sysInstructions.xml');
parseSysInstructions('inst',xml,useSystemInstructions.toUpperCase(),orgId,mockId,isOptionalSectionsAvailable.toUpperCase(),isMarkedForReviewConsidered.toUpperCase(),compMockTime);
}else{
var xml = readAndReturnXML('sysInstructions.xml');
parseSysInstructions('inst',xml,useSystemInstructions.toUpperCase(),orgId,mockId,isOptionalSectionsAvailable.toUpperCase(),isMarkedForReviewConsidered.toUpperCase(),compMockTime);
}
if(document.getElementById("basInst").options.length>1){
$('#basInst').parent().show();
$('#cusInst').parent().show();
$('#defaultLangOptions').prepend("<span>Choose your default language</span>");
$('#defaultLanguage').after("<br/><span class='highlightText'>Please note all questions will appear in your default language. This language can be changed for a particular question later on.</span><br/>");
$('#defaultLangOptions').show();
}
if($('#basInst option:selected').val().indexOf('sysInstText')>-1)
langId = $('#basInst option:selected').val().split('sysInstText')[1];
$('#sysInstText'+langId).show();
$('#cusInstText'+langId).show();
$('#pWait').hide();
}
/*****************quiz page********************************/
var iOAP={}; // contains details of the current group
function setGroupObj(groupObj){
this.groupObj = groupObj;
}
/*var groupObj = {
sections : new Array(),
secDetails : new Array(),
languages : new Array(),
viewLang : new Array(),
modules : ["instructionsDiv","profileDiv","QPDiv","questionCont","sectionSummaryDiv"],
showMarks : false,
showQType : true,
curSection : 1,
curQues : 1,
defaultLang: "",
secWiseTimer: 0,
noOptSec : 0,
maxNoOptSec : '',
time: '' ,
minSubmitTime : ''
}; // Contains the details of a Group
*/
function createNewGroupObj(){
this.sections = new Array();
this.secDetails = new Array();
this.languages = new Array();
this.viewLang = new Array();
this.modules = ["instructionsDiv","profileDiv","QPDiv","questionCont","sectionSummaryDiv"];
this.showMarks = false;
this.showQType = true;
this.curSection = 1;
this.curQues = 1
this.defaultLang = "";
this.secWiseTimer = 0;
this.noOptSec = 0;
this.maxNoOptSec = 0;
this.minTime = 0;
this.maxTime = 0;
this.ellapsedTime = 0;
this.breakTime = 0;
this.isEditable = "N";
this.isViewable= "N";
this.firstNonTimeBoundGrp = false;
this.isDisabled = true;
this.isTypingGroup = false;
return this;
}
var mockVar = {
orgId : '',
mockId : '',
mcQName : '', // mcq questions name
msQName : '', // msq questions name
saQName : '', // SA questions name
compQName : '', // comp questions name
laQName : '', // LA questions name
subjQName:'', // subjective questions name
typingQName:'', // typing question name
modules : ["instructionsDiv","profileDiv","QPDiv","questionCont","sectionSummaryDiv"], // Modules are various div in the question area
groups : new Array(),
typingGroup : new Array(),
currentGrp : 1,
isMarkedForReviewConsidered : '',
time: '' ,
isHelpContentAvailable:false,
helpContent : new Array(),
minSubmitTime : '',
activeLinkList : ['viewQPButton','viewInstructionsButton','viewProButton','finalSub','finalTypingSub'],
totalQues : 0,
showOptionsHorizontally : 0,
showCalculator : 0
}; // contains all the parameters which are common throughout the mock.
function getCookie(){
var i,x,y,returnVal,ARRcookies=document.cookie.split(";");
if(ARRcookies != null && ARRcookies != ""){
for (i=0;i<ARRcookies.length;i++){
x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
x=x.replace(/^\s+|\s+$/g,"");
if (x=="defaultLang"){
returnVal = y
}
}
}else{
window.location.href="error.html?E103";
}
if(returnVal != null && returnVal != ""){
return unescape(y);
}else{
window.location.href="error.html?E103";
}
}
function questions(quesText,quesID,quesType,options,answer,isParent,allottedMarks,negMarks,keyboardType,typingType,typedWord){
this.quesText = quesText;
this.quesID = quesID;
this.answer = answer;
this.typedWord = typedWord;
this.options = options;
this.quesType = quesType;
this.isParent=isParent;
this.allottedMarks = allottedMarks;
this.negMarks = negMarks;
this.keyboardType = keyboardType;
this.typingType = typingType;
}
function secBean(secName,answered,notanswered,marked,isOptional,secType){
this.secName = secName;
this.answered = answered;
this.notanswered = notanswered;
this.marked = marked;
this.isOptional = isOptional;
this.secType = secType;
this.GWPM = 0;
this.NWPM = 0;
this.isSelected = false;
this.maxOptQuesToAns = "";
this.curQues = 1;
}
function quesParams(langID,status){
this.langID = langID;
this.status = status;
}
function validateQuizPageUrl(){
var url = document.URL;
var params = url.split("quiz.html?");
var orgId = $.trim(params[1]).split("@@")[0];
var mockId = $.trim(params[1]).split("@@")[1];
if(mockId != null && mockId.length>0){
if(mockId.indexOf("#")>-1){
mockId = mockId.substring(0,mockId.indexOf("#"));
}
if(params.length>1 && $.trim(params[1]).length>0){
validateExpiry(orgId,mockId);
var xml = readAndReturnXML(orgId+'/'+mockId+'/confDetails.xml');
basicDetails(xml);
mockVar.isFeedBackRequired = $(xml).find("FEEDBACKREQUIRED ").text();
if($(xml).find("SHOWEMAILID").text() == "YES"){
$("#emailIdText").html("<b>Email ID</b>");
$("#emailId").html("<b> : </b>somebody@gmail.com ");
}
if($(xml).find("SHOWCONTACTNO").text() == "YES"){
$("#contactNoText").html("<b>Contact No</b>");
$("#contactNo").html("<b> : </b>9999999999");
}
/*
//Check of backward compatibility. The GROUPDEPENDENTTIME tag is not present in the older versions (v10)
if($(xml).find("GROUPDEPENDENTTIME").length>0){
if($(xml).find("GROUPDEPENDENTTIME").text().toUppercase()="YES"){
mockVar.isGroupDependentTime = true;
}else{
mockVar.isGroupDependentTime = false;
}
if($(xml).find("SHOWATTEMPTEDGROUPS").text().toUppercase()="YES"){
mockVar.showAttemptedGroups = true;
}else{
mockVar.showAttemptedGroups = false;
}
if($(xml).find("EDITATTEMPTEDGROUPS").text().toUppercase()="YES"){
mockVar.editAttemptedGroups = true;
}else{
mockVar.editAttemptedGroups = false;
}
}else{
mockVar.isGroupDependentTime = true;
}
*/
mockVar.orgId = orgId;
mockVar.mockId = mockId;
readSysInstructionsXMLQuizPage(xml,orgId,mockId);
}
}else{
window.location.href="error.html";
}
/*else{
window.location.href="error.html";
}*/
}
function readSysInstructionsXMLQuizPage(xml,orgId,mockId){
var useSystemInstructions = $(xml).find("USESYSTEMINSTRUCTIONS").text();
var compMockTime = $(xml).find("COMPLETEMOCKDURATION").text();
mockVar.completeTime = compMockTime;
var counter =1;
var langId = 1;
/*var tempcounter =0;
while(counter!=tempcounter){
tempcounter = counter;
$(xml).find("USEFULDATAFILE"+counter).each(function(){
if($(this).text()!=null && $.trim($(this).text()) != ""){
mockVar.helpContent[counter] = $(this).text();
mockVar.isHelpContentAvailable = true;
}
counter++;
});
}*/
for(counter=1;counter<=15;counter++){
$(xml).find("USEFULDATAFILE"+counter).each(function(){
if($(this).text()!=null && $.trim($(this).text()) != ""){
mockVar.helpContent[counter] = $(this).text();
mockVar.isHelpContentAvailable = true;
}
});
}
mockVar.minSubmitTime = $(xml).find("COMPULSORYTIME").text();
var isOptionalSectionsAvailable = $(xml).find("ISOPTIONALSECTIONSAVAILABLE").text();
var isMarkedForReviewConsidered = $(xml).find("ISMARKEDFORREVIEWCONSIDERED").text();
mockVar.isMarkedForReviewConsidered = isMarkedForReviewConsidered;
if($(xml).find("USEBANNER").text().toLowerCase() == "yes"){
$("#bannerImage").html("<img height= '45px' src='"+$(xml).find("BANNERPATH").text()+"'/>");
}else{
$("#bannerImage").html('<div style="margin-top:10px"><font size=4 color="#ffffff"><b>'+$(xml).find("BANNERTEXT").text()+'</b></font></div>');
$("#bannerImage").attr("align","center");
}
$("#footer").html("<div style='width:100%;padding-top:15px;'><center><font color='white'> Version : " +$(xml).find("VERSION").text()+"</font></center></div>");
if(useSystemInstructions.toUpperCase()=="NO"){
var xml =readAndReturnXML(orgId+'/'+mockId+'/sysInstructions.xml');
parseSysInstructions('quiz',xml,useSystemInstructions.toUpperCase(),orgId,mockId,isOptionalSectionsAvailable.toUpperCase(),isMarkedForReviewConsidered.toUpperCase(),compMockTime);
}else{
var xml = readAndReturnXML('sysInstructions.xml');
parseSysInstructions('quiz',xml,useSystemInstructions.toUpperCase(),orgId,mockId,isOptionalSectionsAvailable.toUpperCase(),isMarkedForReviewConsidered.toUpperCase(),compMockTime);
}
if(document.getElementById("basInst").options.length>1){
$('#basInst').parent().show();
$('#cusInst').parent().show();
}
if($('#basInst option:selected').val().indexOf('sysInstText')>-1)
langId = $('#basInst option:selected').val().split('sysInstText')[1];
$('#sysInstText'+langId).show();
$('#cusInstText'+langId).show();
var QPxml = readAndReturnXML(orgId+'/'+mockId+'/quiz.xml');
readXMLQuestionPaper(QPxml);
}
function renderQuestions(xml,selectorElement){
$(xml).find(selectorElement).each(function(){
//iOAP = groupObj;
/*iOAP.secDetails = new Array();
iOAP.languages = new Array();
iOAP.sections = new Array();
iOAP.viewLang = new Array();*/
iOAP = new createNewGroupObj();
typingGrpObj = new typingObject();
$(this).find("SECTION").each(function(){
secCounter = $(this).find("secID").text();
langCounter = $(this).find("LANGID").text();
quesCounter = 1;
if(iOAP.secDetails[secCounter] == null){
var secName = $(this).find("secName").text();
var answered = 0;
var notanswered = 0;
var marked = 0;
var isOptional = $(this).attr("ISOPTIONAL");
if(isOptional == 'Y'){
iOAP.noOptSec++;
}
var secType = $(this).attr('TYPE')?$(this).attr('TYPE'):"";
iOAP.secDetails[secCounter] = new secBean(secName,answered,notanswered,marked,isOptional,secType);
if($(this).attr("MAXQUESTOANS")!=null || $(this).attr("MAXQUESTOANS") != ""){
iOAP.secDetails[secCounter].maxOptQuesToAns = parseInt($(this).attr("MAXQUESTOANS"));
}
}
if(iOAP.languages[langCounter] == null){
iOAP.languages[langCounter] = $(this).find("LANGNAME").text();
}
if(iOAP.sections[secCounter] == null){
iOAP.sections[secCounter] = new Array();
}
if(iOAP.sections[secCounter][langCounter] == null){
iOAP.sections[secCounter][langCounter] = new Array();
}
if(iOAP.viewLang[secCounter] == null){
iOAP.viewLang[secCounter] = new Array();
}
$(this).find("QUESTION").each(function(){
var quesText = $(this).find("NAME").text();
var quesType = $(this).attr("TYPE");
var options = new Array();;
if(quesType != "SA" || quesType != "SUBJECTIVE" || quesType != "COMPREHENSION@@SA"){
var optCounter = 0;
$(this).find("ANSWER").each(function(){
options[optCounter] = $(this).text();
optCounter++;
});
}
if(iOAP.viewLang[secCounter][quesCounter] == null){
if(iOAP.defaultLang==null || iOAP.defaultLang ==""){
iOAP.defaultLang = getCookie();
}
iOAP.viewLang[secCounter][quesCounter] = new quesParams(iOAP.defaultLang,'notAttempted');
}
var isParent = false;
var keyboardType = 0;
var typingType = 0;
if(quesType.indexOf("LA")>-1 ||quesType.indexOf("COMPREHENSION")>-1){
if($(this).attr("ISPARENT")=="Y")
isParent = true;
}
if(quesType.indexOf("SA")>-1){
keyboardType = $(this).find("KEYBOARDTYPE").text();
}
else if(quesType.indexOf('TYPING')>-1){
typingType = $(this).find("TYPINGTYPE").text();
}
var allottedMarks = $(this).find("ALLOTTEDMARKS").text();
var negMarks = $(this).find("NEGATIVEMARKS").text();
var question = new questions(quesText,quesCounter,quesType,options,'',isParent,allottedMarks,negMarks,keyboardType,typingType,'');
iOAP.sections[secCounter][langCounter][quesCounter] = question;
quesCounter++;
});
});
mockVar.groups.push(iOAP);
mockVar.typingGroup.push(typingGrpObj);
});
}
function readXMLQuestionPaper(xml){
var secCounter,quesCounter = 1,langCounter;
mockVar.time = mockVar.time*60;
//alert(mockVar.time+":::"+mockVar.minSubmitTime);
iOAP.maxNoOptSec = $(xml).find("MAXNOOPTSEC").text();
var isShowMarks = $(xml).find("SHOWMARKS").text();
mockVar.showMarks = (isShowMarks.toUpperCase()=="YES")?true:false;
mockVar.mcQName = $.trim($(xml).find("mcQName").text());
mockVar.msQName = $.trim($(xml).find("msQName").text());
mockVar.compQName = $.trim($(xml).find("compQName").text());
mockVar.laQName = $.trim($(xml).find("laQName").text());
mockVar.saQName = $.trim($(xml).find("saQName").text());
mockVar.subjQName = $.trim($(xml).find("subjQName").text());
mockVar.typingQName = $.trim($(xml).find("typingQName").text());
//To handle older mock which do not contain <GROUP> tag in the XML. Backward compatibility
mockVar.nonTimeBoundTime = 0;
//Convert total time to seconds
mockVar.completeTime = mockVar.completeTime*60;
mockVar.minSubmitTime= mockVar.completeTime*mockVar.minSubmitTime/100;
//alert();
if($(xml).find("GROUP").length>0){
renderQuestions(xml,"GROUP");
var counter = 0;
var totTimeBoundTime = 0;
var firstNonTimeBoundGrp = true;
$(xml).find("GROUP").each(function(){
mockVar.groups[counter].maxTime = parseInt($(this).attr("MAXTIME")) *60;
if($(this).attr("MAXTIME") >0){
totTimeBoundTime += parseInt($(this).attr("MAXTIME"))*60;
}else if( firstNonTimeBoundGrp && $(this).attr("MAXTIME")==0){
mockVar.groups[counter].firstNonTimeBoundGrp = true;
firstNonTimeBoundGrp = false;
}
mockVar.groups[counter].minTime = parseInt($(this).attr("MINTIME"))*60;
mockVar.groups[counter].breakTime = parseInt($(this).attr("BREAKTIME"))*60;
mockVar.groups[counter].isViewable = $(this).attr("ISVIEWABLE").toUpperCase();
mockVar.groups[counter].isEditable = $(this).attr("ISEDITABLE").toUpperCase();
mockVar.groups[counter].groupName = $(this).find("GROUPNAME").text();
mockVar.groups[counter].maxNoOptSec = $(this).find("MAXNOOPTSEC").text();
counter++;
});
mockVar.nonTimeBoundTime = mockVar.completeTime - totTimeBoundTime;
}else{
renderQuestions(xml,"SECTIONDETAILS");
mockVar.groups[0].maxNoOptSec = $(xml).find("MAXNOOPTSEC").text();
mockVar.groups[0].minTime = mockVar.minSubmitTime;
mockVar.groups[0].maxTime = mockVar.completeTime;
}
mockVar.currentGrp = 0;
mockVar.MaxGrpEnabled=0;
iOAP = mockVar.groups[mockVar.currentGrp];
mockVar.groups[mockVar.currentGrp].isDisabled = false;
if(mockVar.groups[mockVar.currentGrp].maxTime>0){
mockVar.time = mockVar.groups[mockVar.currentGrp].maxTime;
}else if(mockVar.groups.length>1 && mockVar.groups[mockVar.currentGrp].maxTime ==0){
mockVar.time = mockVar.nonTimeBoundTime;
}else{
mockVar.time = mockVar.completeTime;
}
mockVar.minSubmitTime = mockVar.groups[mockVar.currentGrp].minTime;
numPanelSec();
getQuestion();
fillSections();
fillNumberPanel();
if(iOAP.noOptSec>0){
$('#showOptionalSecSummary').show();
$('#noOptSec').html(iOAP.noOptSec);
$('#maxOptSec').html(iOAP.maxNoOptSec);
}
if(iOAP.secDetails.length>6 && !$.browser.msie ){
$('#questionCont').css({"height":"68%"});
$('#instructionsDiv').css({"height":"68%"});
$('#profileDiv').css({"height":"68%"});
$('#QPDiv').css({"height":"68%"});
$('#sectionSummaryDiv').css({"height":"68%"});
}
$("#pWait").hide();
$('#viewQPButton').removeAttr("disabled"); // View QP button is getting disabled after refresh because of numpad_keyboard.js. Wierd behaviour
$('#viewProButton').removeAttr("disabled");// View Profile button is getting disabled after refresh because of numpad_keyboard.js. Wierd behaviour
$('#viewInstructionsButton').removeAttr("disabled");
}
function getQuestion(){
// $("#loadCalc").hide();
// $("#showCalc").show();
var ques = iOAP.viewLang[iOAP.curSection][iOAP.curQues];
if(ques.status == "notAttempted"){
iOAP.viewLang[iOAP.curSection][iOAP.curQues].status = "notanswered";
iOAP.secDetails[iOAP.curSection].notanswered++;
}
fillSections();
var question = iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues];
$('#currentQues').html(quesContent(question));
var quesText = question.quesText;
if(quesText.indexOf("@@&&")>-1)
quesText = quesText.split("@@&&")[1];
if(quesText==null || quesText=="" || quesText==" "){
for(i=1;i<iOAP.languages.length;i++){
if(iOAP.languages[i]!=null || typeof(iOAP.languages[i])!='undefined'){
question=iOAP.sections[iOAP.curSection][i][iOAP.curQues];
quesText = question.quesText;
if(quesText.indexOf("@@&&")>-1)
quesText = quesText.split("@@&&")[1];
if(!(quesText==null || quesText=="" || quesText==" ")){
iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID=i;
break;
}
}
}
getQuestion();
}
else{
if(question.quesType == "SA" || question.quesType == "LA@@SA" || question.quesType == "COMPREHENSION@@SA"){
// numeric keyboard
if(question.keyboardType == 1){
$('#answerArea').after("<div style='padding-left: 4%;'><input type='text' id='answer' class='keyboardInput' value='"+question.answer+"'/></div>");
triggerKeyboard(question.keyboardType);
}
// alphanumeric textarea
else if(question.keyboardType == 2){
$('#answerArea').after('<div style="padding-left: 4%;"> <span id="noOfWords" name="noOfWords" style="font-size: 12px;color:#2F72B7;font-weight: bold;">&nbsp;'+question.typedWord+'</span><p> <textarea rows="7" cols="75" name="option" autocomplete="off" id="answer" onkeydown="disableTab(event);" onkeypress="return validateKeyBoardInputAlphaNumeric(event, this);" onkeyup="word_count();">'+question.answer+'</textarea></p></div>');
}
if(iOAP.secDetails[iOAP.curSection].isOptional == 'Y' && !iOAP.secDetails[iOAP.curSection].isSelected){
if(question.keyboardType == 2)
$('#answer').attr('disabled','disabled');
}else{
focusOnDiv();
}
}
if(question.quesType == "TYPING"){
focusOnDiv();
$("#row1 span:first").addClass('highlight');
$('#sectionsField').hide();
$('.questionTypeCont .marks').hide();
$('#actionButton').hide();
$('#legend').hide();
$('#quesPallet').hide();
$('#numberpanelQues').hide();
$('#typingSubmit').show();
$('#typingInstDiv').show();
$('#showCalc').hide();
if(question.typingType == 1){
$('#errorCount').show();
$('#restrictedInstr').show();
$('#unrestrictedInstr').hide();
}else{
$('#errorCount').hide();
$('#restrictedInstr').hide();
$('#unrestrictedInstr').show();
}
}else{
$('#sectionsField').show();
$('#actionButton').show();
$('#legend').show();
$('#quesPallet').show();
$('#numberpanelQues').show();
$('#typingSubmit').hide();
$('#typingInstDiv').hide();
if(mockVar.showCalculator)
$('#showCalc').show();
}
quizPageHeight();
}
/*if(iOAP.curQues>19){
var el = document.getElementById(iOAP.curQues);
el.scrollIntoView(true);
}*/
scrollIntoView(document.getElementById('qtd'+iOAP.curQues),document.getElementById('numberpanelQues'));
enableOptButtons();
}
function scrollIntoView(element, container) {
try{
var containerTop = $(container).scrollTop();
var containerBottom = containerTop + $(container).height();
var elemTop = element.offsetTop;
$(container).scrollTop(elemTop - $(element).height());
}catch(err){
}
}
function isScrolledIntoView(elem)
{
var docViewTop = $(window).scrollTop();
var docViewBottom = docViewTop + $(window).height();
var elemTop = $(elem).offset().top;
var elemBottom = elemTop + $(elem).height();
return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
function bindCharCode(){
$('input').bind('keydown',function(event){
var code = (event.keyCode ? event.keyCode : event.which);
if(code == 8){
$(this).val($(this).val().substring(0,$(this).val().length -1));
}else if(!((code > 44 && code < 58) || (code > 64 && code < 91) || (code>94 && code<123) || code==43 || code == 16 || code == 32)){
return false;
}
});
}
function applyKeyboard(){
var div = document.getElementById('currentQues');
var input = div.getElementsByTagName('input');
if (input.length) {
VKI_attach(input[0]);
}
}
function fillMCQQues (quesTxt,quesOpts,answer){
var str = "<div id='quesAnsContent' style='height:92%;overflow:auto;'>";
str+= "<div style='width:99%;margin-left:5px;font-family:Arial,verdana,helvetica,sans-serif;padding-bottom:10px;'> "+quesTxt+ "</div>";
str+= "<div style='width:100%;font-family:Arial,verdana,helvetica,sans-serif;margin-top:5px;'><table>";
var answers = answer.split(",");
if(mockVar.showOptionsHorizontally)
str+= '<tr>';
for(var i=0;i<quesOpts.length;i++){
if(!mockVar.showOptionsHorizontally)
str+= '<tr>';
str += "<td><input type='radio' onMouseDown='this.check = this.checked' onClick='if (this.check) this.checked = false' name='answers' value='"+i+"' ";
for(var j=0;j<answers.length;j++){
if(i==answers[j] && answers[j]!="")
str += "checked";
}
str +="/> </td><td style='font-family:Arial,verdana,helvetica,sans-serif;padding-right:60px'>"+quesOpts[i]+" </td>";
if(!mockVar.showOptionsHorizontally)
str+= '</tr>';
}
if(!mockVar.showOptionsHorizontally)
str+= '</tr>';
str += "</table></div></div>";
return str;
}
function fillMSQQues (quesTxt,quesOpts,answer){
var answers = answer.split(",");
var str = "<div id='quesAnsContent' style='height:92%;overflow:auto;'>";
str+= "<div style='width:99%;margin-left:5px;font-family:Arial,verdana,helvetica,sans-serif;padding-bottom:10px;'> "+quesTxt+ "</div>";
str+= "<div style='width:100%;font-family:Arial,verdana,helvetica,sans-serif;margin-top:5px;'><table>";
if(mockVar.showOptionsHorizontally)
str+= '<tr>';
for(var i=0;i<quesOpts.length;i++){
if(!mockVar.showOptionsHorizontally)
str+= '<tr>';
str += "<td><input type='checkbox' name='answers' value='"+i+"' ";
for(var j=0;j<answers.length;j++){
if(i==answers[j] && answers[j]!="")
str += "checked";
}
str +="/> </td><td style='font-family:Arial,verdana,helvetica,sans-serif;padding-right:60px'>"+quesOpts[i]+" </td>";
if(!mockVar.showOptionsHorizontally)
str+= '</tr>';
}
if(!mockVar.showOptionsHorizontally)
str+= '</tr>';
str += "</table></div></div>";
return str;
}
function fillSAQues(quesTxt,answer){
var str = "<div id='quesAnsContent' style='height:92%;overflow:auto;'>";
str+= "<div style='width:99%;margin-left:5px;font-family:Arial,verdana,helvetica,sans-serif;padding-bottom:10px;'> "+quesTxt+ "</div>";
str+= "<div id='answerArea' style='width:100%;font-family:Arial,verdana,helvetica,sans-serif;margin-top:5px;'><br/><br/></div></div>";
return str;
}
function fillRestrictedTypingQues(quesTxt){
$('#dataDisplayDiv').html(quesTxt.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;'));
mockVar.typingGroup[mockVar.currentGrp].textForRestrictedTyping = $('#dataDisplayDiv').text();
var str = loadTypingContentRestricted(); //in restrictedTyping.js
return str;
}
function fillUnrestrictedTypingQues(quesTxt){
$('#dataDisplayDiv').html(quesTxt.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;'));
mockVar.typingGroup[mockVar.currentGrp].word_string = $('#dataDisplayDiv').text();
var str = loadTypingContentUnrestricted();
return str;
}
var allowedChars = new Array("+","-");
function numPadValidate(text) {
var proceed = true;
for(var i=0;i<allowedChars.length;i++){
if(text.indexOf(allowedChars[i])>0){
proceed=false;
}
if(text.split(allowedChars[i]).length>2){
proceed = false;
}
}
if(text.indexOf('.') > -1){
var afterDot = text.split(".");
if(afterDot.length==2){
if(afterDot[1].length>2)
proceed=false;
}else if(afterDot.length>2){
proceed=false;
}
}
return proceed;
}
function fillCompQues(ques){
var str ='<div style="width:100%; margin-top:5px;height:92%">';
str += '<div id="compreText" style="width:49%;float:left;margin-left:5px;font-family:Arial,verdana,helvetica,sans-serif; overflow:auto;height:100%">';
str += (mockVar.compQName.length>0)?('<table width="100%"><tr><td><div style="font-size:1em;font-family:Arial,verdana,helvetica,sans-serif"><b>'+mockVar.compQName+'</b></div></td></tr><tr><td><hr/></td></tr></table>') : "";
str += ques.quesText.split("@@&&")[0]+' </div>';
return str;
}
function fillSubjectiveQues(quesTxt){
var str = "<div style='width:98%;margin-left:5px;font-family:Arial,verdana,helvetica,sans-serif;overflow:auto'> "+quesTxt+ "</div>";
return str;
}
function fillQuesNumber(ques){
var str = '<div style="width:98%;height:6%;border-bottom:1px solid #000000;margin:5px;"><div style="float : left;width:49%;font-size:1em;font-family:Arial,verdana,helvetica,sans-serif"><b> Question No. '+ques.quesID+'</b></div><div style="width:49%;float:right;">';
if(iOAP.languages.length>2){
str += "<div style='float:right'> <b>View In : </b> <select onchange='changeLang(this.value)'> ";
for(var i=1;i<iOAP.languages.length;i++){
if(iOAP.languages[i]!=null || typeof(iOAP.languages[i])!='undefined'){
str +="<option";
if(i==iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID)
str += " selected='selected'";
str += " value='"+i+"'>"+iOAP.languages[i]+"</option>";
}
}
str +="</select></div>";
}
str += '</div></div>';
return str;
}
function fillLAQuesNumber(ques){
var str = '<div style="width:98%;height:6%;border-bottom:1px solid #000000;margin:5px;"><div style="float : left;width:49%;font-size:1em;font-family:Arial,verdana,helvetica,sans-serif"><b>Question No. '+ques.quesID;
str += ($.trim(ques.quesText.split("@@&&")[0]).length <= 0 && mockVar.laQName.length >0 )?(" ("+mockVar.laQName+")"):"";
str +='</b></div><div style="width:49%;float:right;">';
if(iOAP.languages.length>2){
str += "<div style='float:right'> View In <select onchange='changeLang(this.value)'> ";
for(var i=1;i<iOAP.languages.length;i++){
if(iOAP.languages[i]!=null || typeof(iOAP.languages[i])!='undefined'){
str +="<option";
if(i==iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID)
str += " selected='selected'";
str += " value='"+i+"'>"+iOAP.languages[i]+"</option>";
}
}
str +="</select></div>";
}
str += '</div></div>';
return str;
}
function fillLAQues(ques){
var str ='<div style="width:98%; margin-top:5px;height:92%">';
str += '<div style="width:49%;float:left;margin-left:5px;font-family:Arial,verdana,helvetica,sans-serif;overflow:auto;height:98%">';
str += (mockVar.laQName.length>0)?('<table width="100%"><tr><td><div style="font-size:1em;font-family:Arial,verdana,helvetica,sans-serif"><b>'+mockVar.laQName+'</b></div></td></tr><tr><td><hr/></td></tr></table>'):'';
str += "<p>" +ques.quesText.split("@@&&")[0] +"</p>";
if(iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues-1]!=null){
parentQues = iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues-1];
if(parentQues.isParent){
if($.trim(parentQues.answer) != ""){
str += "<p><i>Selected answer(s) of the previous question is :";
if(parentQues.quesType.indexOf("SA") ==-1){
var answers = parentQues.answer.split(",");
for(var j=0;j<answers.length;j++){
str += parentQues.options[answers[answers.length - j-1]] + ",";
}
str = str.substring(0,str.length-1);
}else{
str += parentQues.answer;
}
str += "</i></p>";
}
}
}
str += ' </div>';
return str;
}
function changeLang(langID){
//iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID = langID;
var question = iOAP.sections[iOAP.curSection][langID][iOAP.curQues];
var quesText = question.quesText;
if(quesText.indexOf("@@&&")>-1)
quesText = quesText.split("@@&&")[1];
if(quesText==null || quesText=="" || quesText==" "){
alert("This Question is not available in "+iOAP.languages[langID]);
question = iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues]
$('#currentQues').html(quesContent(question));
if(question.quesType == "SA" || question.quesType == "LA@@SA" || question.quesType == "COMPREHENSION@@SA"){
// numeric keyboard
if(question.keyboardType == 1){
$('#answerArea').after("<div style='padding-left: 4%;'><input type='text' id='answer' class='keyboardInput' value='"+question.answer+"'/></div>");
triggerKeyboard(question.keyboardType);
}
// alphanumeric textarea
else if(question.keyboardType == 2){
$('#answerArea').after('<div style="padding-left: 4%;"> <span id="noOfWords" name="noOfWords" style="font-size: 12px;color:#2F72B7;font-weight: bold;">&nbsp;'+question.typedWord+'</span><p> <textarea rows="7" cols="75" name="option" autocomplete="off" id="answer" onkeydown ="disableTab();" onkeypress="return validateKeyBoardInputAlphaNumeric(event, this);" onkeyup="word_count();">'+question.answer+'</textarea></p></div>');
}
}
}
else{
iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID=langID;
getQuestion();
}
}
function fillQuesDetailsCont(ques){
var str = "";
if(iOAP.showQType){
str +="<span class='content'>";
if(ques.quesType=="MCQ" && $.trim(mockVar.mcQName).length>0){
str += "Question Type : "+ mockVar.mcQName;
}else if(ques.quesType=="MSQ" && $.trim(mockVar.msQName).length>0){
str += "Question Type : "+mockVar.msQName;
}else if(ques.quesType=="SA" && $.trim(mockVar.saQName).length>0){
str += "Question Type : "+mockVar.saQName;
}else if(ques.quesType == "SUBJECTIVE" && $.trim(mockVar.subjQName).length>0){
str += "Question Type : "+mockVar.subjQName;
}else if(ques.quesType == "COMPREHENSION@@MCQ" && $.trim(mockVar.mcQName).length>0){
str+="Question Type : "+mockVar.mcQName;
}else if(ques.quesType == "COMPREHENSION@@MSQ" && $.trim(mockVar.msQName).length>0){
str+="Question Type : "+mockVar.msQName;
}else if(ques.quesType == "COMPREHENSION@@SA" && $.trim(mockVar.saQName).length>0){
str+="Question Type : "+mockVar.saQName;
}else if(ques.quesType == "LA@@MCQ" && $.trim(mockVar.mcQName).length>0){
str+="Question Type : "+mockVar.mcQName;
}else if(ques.quesType == "LA@@MSQ" && $.trim(mockVar.msQName).length>0){
str+="Question Type : "+mockVar.msQName;
}else if(ques.quesType == "LA@@SA" && $.trim(mockVar.saQName).length>0){
str+="Question Type : "+mockVar.saQName;
}else if(ques.quesType == "TYPING" && $.trim(mockVar.saQName).length>0){
str+="Question Type : "+mockVar.typingQName;
}
str += "</span>";
}
if(mockVar.showMarks){
str += "<span class='marks'>Marks for correct answer : <font style='color:green'>"+ques.allottedMarks+"</font>"
str += "; Negative Marks : <font style='color:red'>"+ques.negMarks+"</font></span>";
}
return str;
}
function quesContent(ques){
var str='' ;
$("#savenext").val("Save & Next") ;
if(mockVar.showMarks || iOAP.showQType){
// console.log("in");
if(ques.quesType=="MCQ" && ($.trim(mockVar.mcQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType=="MSQ" && ($.trim(mockVar.msQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType=="SA" && ($.trim(mockVar.saQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "SUBJECTIVE" && ($.trim(mockVar.subjQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "COMPREHENSION@@MCQ" && ($.trim(mockVar.mcQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "COMPREHENSION@@MSQ" && ($.trim(mockVar.msQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "COMPREHENSION@@SA" && ($.trim(mockVar.saQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "LA@@MCQ" && ($.trim(mockVar.mcQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "LA@@MSQ" && ($.trim(mockVar.msQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "LA@@SA" && ($.trim(mockVar.saQName).length>0 || mockVar.showMarks)){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}else if(ques.quesType == "TYPING" && $.trim(mockVar.typingQName).length>0){
str = "<div class='questionTypeCont'>";
str += fillQuesDetailsCont(ques);
str += "</div>";
}
}
if(ques.quesType == "MCQ"){
str += "<div style='height:93%;width:100%'>";
str += fillQuesNumber(ques);
str += fillMCQQues(ques.quesText,ques.options,ques.answer);
str += "</div>";
}else if(ques.quesType == "MSQ"){
str += "<div style='height:93%;width:100%'>";
str += fillQuesNumber(ques);
str += fillMSQQues(ques.quesText,ques.options,ques.answer);
str += "</div>";
}else if(ques.quesType == "SA"){
str += "<div style='height:93%;width:100%'>";
str += fillQuesNumber(ques);
str += fillSAQues(ques.quesText,ques.answer,ques.quesID);
str += "</div>";
}else if(ques.quesType == "SUBJECTIVE"){
$("#savenext").val("Mark as Answered") ;
str += "<div style='height:93%;'>";
str += fillQuesNumber(ques);
str += fillSubjectiveQues(ques.quesText);
str += "</div>";
}else if(ques.quesType == "COMPREHENSION@@MCQ" || ques.quesType == "COMPREHENSION@@MSQ" || ques.quesType == "COMPREHENSION@@SA"){
str += fillCompQues(ques);
str += '<div id="compreQuesText" style="width:49%;float:right;border: solid 0 #000; border-left-width:1px;height:100%">';
str += fillQuesNumber(ques);
if(ques.quesType == "COMPREHENSION@@MCQ" ){
str += fillMCQQues(ques.quesText.split("@@&&")[1],ques.options,ques.answer);
}else if(ques.quesType == "COMPREHENSION@@MSQ" ){
str += fillMSQQues(ques.quesText.split("@@&&")[1],ques.options,ques.answer);
}else if(ques.quesType == "COMPREHENSION@@SA" ){
str += fillSAQues(ques.quesText.split("@@&&")[1],ques.answer);
}
str +='</div></div>';
}else if(ques.quesType == "LA@@MCQ" || ques.quesType == "LA@@MSQ" || ques.quesType == "LA@@SA"){
var laQues = ques.quesText.split("@@&&");
if($.trim(laQues[0]).length >0){
str += fillLAQues(ques);
str += '<div id="LAQuesText" style="width:49%;float:right;border: solid 0 #000; border-left-width:1px;height:100%">';
}else{
str += "<div id='LAQuesText' style='height:93%;'>";
}
str += fillLAQuesNumber(ques);
if(ques.quesType == "LA@@MCQ" ){
str += fillMCQQues(ques.quesText.split("@@&&")[1],ques.options,ques.answer);
}else if(ques.quesType == "LA@@MSQ" ){
str += fillMSQQues(ques.quesText.split("@@&&")[1],ques.options,ques.answer);
}else if(ques.quesType == "LA@@SA" ){
str += fillSAQues(ques.quesText.split("@@&&")[1],ques.answer);
}
if($.trim(str.split("@@&&")[1]).length >0)
str +='</div></div>';
}else if(ques.quesType == "TYPING"){
$("#savenext").val("Submit") ;
str += "<div style='height:93%;'>";
//str += fillQuesNumber(ques);
if(ques.typingType == 1){ //for Restricted Typing
str += fillRestrictedTypingQues(ques.quesText);
}else if(ques.typingType == 2){ //for Unrestricted typing
str += fillUnrestrictedTypingQues(ques.quesText);
}
str += "</div>";
}
return str;
}
function changeHelpLang(langId){
var str = "<div style='width:100%;height:94%;overflow:auto;border-bottom : 2px solid #CCCCCC'>";
if(iOAP.languages.length>2){
str += "<div style='width:100%'>";
str += "<span style='float:right'> View In <select onchange='changeHelpLang(this.value)'> ";
for(var i=1;i<iOAP.languages.length;i++){
if(iOAP.languages[i]!=null || typeof(iOAP.languages[i])!='undefined'){
str +="<option";
if(i==langId)
str += " selected='selected'";
str += " value='"+i+"'>"+iOAP.languages[i]+"</option>";
}
}
str +="</select></span></div>";
}
if(mockVar.helpContent[langId]!= null && $.trim(mockVar.helpContent[langId]) != ""){
str += "<img src='"+mockVar.helpContent[langId]+"'/>";
}else{
str += "Help content is not available in the language selected";
}
str += "</div>";
str +="<div style='overflow : hidden; height : 5%'><table align='center'>";
str +='<tr><td style="text-align:center; padding-top : 5%"><input onclick="showModule(';
str +="'questionCont'";
str +=')" type="button" class="button" value="Back"/></td></tr></table></div>';
$('#sectionSummaryDiv').html(str);
showModule('sectionSummaryDiv');
}
function showHelpContent(event){
if(avoidKeyPressing(event)){
var str = '<div style="overflow : auto; height : 94%; border-bottom : 2px solid #CCCCCC">';
if(iOAP.languages.length>2){
str += "<div style='float:right'> View In <select onchange='changeHelpLang(this.value)'> ";
for(var i=1;i<iOAP.languages.length;i++){
if(iOAP.languages[i]!=null || typeof(iOAP.languages[i])!='undefined'){
str +="<option";
if(i==iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID)
str += " selected='selected'";
str += " value='"+i+"'>"+iOAP.languages[i]+"</option>";
}
}
str +="</select></div>";
}
if(mockVar.helpContent[iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID]!= null && $.trim(mockVar.helpContent[iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID]) != ""){
str += "<img src='"+mockVar.helpContent[iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID]+"'/>";
}else{
str += "Help content is not available in the language selected";
}
str += "</div>";
str +="<div style='overflow : hidden; height : 5%'><table align='center'>";
str +='<tr><td style="text-align:center; padding-top : 5%"><input onclick="showModule(';
str +="'questionCont'";
str +=')" type="button" class="button" value="Back"/></td></tr></table></div>';
$('#sectionSummaryDiv').html(str);
showModule('sectionSummaryDiv');
}
}
function fillGroups(){
iOAP=mockVar.groups[mockVar.currentGrp];
$("#groups").empty();
var tempstr= "" ;
if(mockVar.isHelpContentAvailable){
tempstr = "<div style='width:100%'><a id='usefulData' style='float:right' href='#' onclick='showHelpContent(event);'><b>Useful Data</b></a></div>";
}
$("#groups").html(tempstr);
if(mockVar.groups.length>1){
str="<table width='100%'>";
var tempiOAP ;
for(i=0;i< mockVar.groups.length ;i++){
tempiOAP = mockVar.groups[i];
var langId = 0;
var answeredQuestions = 0;
var notAnsweredQuestions =0;
var markedQuestions =0;
var noOfQuestions =0;
var notAttemptedQuestions = 0;
var grossKeyStrokesCount = 0;
var backSpaceCount = 0;
var elapsedTime = 0;
for(var k=1;k<iOAP.languages.length;k++){
if(iOAP.languages[k]!=null || typeof(iOAP.languages[k])!='undefined'){
langId=k;
break;
}
}
if(tempiOAP.secDetails[1].secType == 'TYPING'){
mockVar.groups[i].isTypingGroup = true;
grossKeyStrokesCount = mockVar.typingGroup[i].keyStrokesCount;
backSpaceCount = mockVar.typingGroup[i].backSpaceCount;
ellapsedTime = tempiOAP.ellapsedTime;
}
for(var j=1;j<tempiOAP.secDetails.length;j++){
answeredQuestions += tempiOAP.secDetails[j].answered;
notAnsweredQuestions += tempiOAP.secDetails[j].notanswered;
markedQuestions += tempiOAP.secDetails[j].marked;
noOfQuestions += tempiOAP.sections[j][langId].length;
notAttemptedQuestions += tempiOAP.sections[j][langId].length - tempiOAP.secDetails[j].marked - tempiOAP.secDetails[j].notanswered - tempiOAP.secDetails[j].answered-1;
}
if(i%5==0){
str+="</td></tr>";
str+="<tr><td>";
}
str+='<div class="allSections" id="g'+i+'" ><a href="#" class="tooltip';
if(mockVar.groups[i].isDisabled){
str += " disabled ";
}
str+='">';
str+='<div style="text-overflow:ellipsis;width:90%;overflow:hidden;white-space:nowrap;padding-left:10px;cursor:pointer;">';
str += mockVar.groups[i].groupName+'</div>';
if(!mockVar.groups[i].isDisabled){
if(!(i==mockVar.MaxGrpEnabled && mockVar.groups[i].isTypingGroup)){
str += '<span class="classic"><center><table width="95%" style="font-size:14px;margin-top:10px" class="question_area" cellspacing="0">';
str += '<tr><td colspan="2"><b>'+mockVar.groups[i].groupName+'</b></td></tr>';
str += '<tr><td colspan="2"><hr/></td></tr></table>';
str += '<table width="95%" style="margin-top:0%" class="question_area" cellspacing="5">';
if(mockVar.groups[i].isTypingGroup){
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Gross KeyStrokes Count: </td><td valign="top">'+grossKeyStrokesCount+'</td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Back Space Count: </td><td valign="top">'+backSpaceCount+'</td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Elapsed Time(In Mins): </td><td valign="top">'+(ellapsedTime/60).toFixed(2)+'</td></tr>';
str += '</table></center></span>';
}else{
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Answered: </td><td valign="top"><span id="tooltip_answered">'+answeredQuestions+'</span></td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Not Answered: </td><td valign="top"><span id="tooltip_not_answered">'+notAnsweredQuestions+'</span></td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Marked for Review: </td><td valign="top"><span id="tooltip_review">'+markedQuestions+'</span></td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Not Visited: </td><td valign="top"><span id="tooltip_not_visited">'+notAttemptedQuestions+'</span></td></tr>';
str += '</table></center></span>';
}
}
}
str += '</a></div>';
}
str +="</td></tr></table>";
$('#groups').append(str);
//align
if( mockVar.groups.length>4 && $.browser.msie ){
for(var i=4;i<iOAP.secDetails.length;i=i+5){
$('#g'+(i+1)+" .tooltip").hover(
function(){ $(this).find(".classic").css({"margin-left":"-60px"});}
, function(){$(this).find(".classic").css({"margin-left":"-999px"});});
}
}
$("#g"+mockVar.currentGrp).addClass("currentSectionSelected");
if(!mockVar.groups[mockVar.MaxGrpEnabled].isTypingGroup){
$("#g"+mockVar.currentGrp+" a").addClass("tooltipSelected");
} else{
$("#g"+mockVar.currentGrp+" a").addClass("tooltipSelected");
}
$("#groups .allSections").click(function (event){
if(event.target.type!="checkbox"){
changeGroup(this.id.split("g")[1]);
}
});
}
}
function checkGroupBreakTime(){
if(mockVar.currentGrp < mockVar.groups.length-1){
if(!(mockVar.groups[mockVar.currentGrp].breakTime == 0)){
clearTimeout(mockVar.timeCounter);
mockVar.timeCounter = mockVar.groups[mockVar.currentGrp].breakTime;
breakTimeCounter(mockVar.timeCounter);
submitConfirmation('break');
}else{
saveSAAlphanumeric();
submitGroup();
}
}else{
moveToFeedback();
}
}
function submitGroup(){
//if(mockVar.currentGrp)
// if(mockVar.currentGrp < mockVar.groups.length-1){
if($('#typedAnswer')){
fnSubmit('NEXT');
$('#typedAnswer').attr('disabled',true);
$('#finalTypingSub').attr('disabled',true);
$('#finalTypingSub').removeClass().addClass('typingTestButtonDisabled');
}
$('#breakTimeDiv').hide();
$('#questionContent').show();
if(mockVar.groups[mockVar.currentGrp].maxTime == 0){
mockVar.nonTimeBoundTime = mockVar.time ;
}
mockVar.currentGrp++;
mockVar.MaxGrpEnabled=mockVar.currentGrp;
if(mockVar.groups[mockVar.currentGrp].maxTime > 0){
mockVar.time = mockVar.groups[mockVar.currentGrp].maxTime;
}else{
mockVar.time = mockVar.nonTimeBoundTime;
}
mockVar.groups[mockVar.currentGrp].isDisabled = false;
mockVar.minSubmitTime = mockVar.groups[mockVar.currentGrp].minTime;
showModule("questionCont");
fillGroups();
getQuestion();
numPanelSec();
fillSections();
enableOptButtons();
fillNumberPanel();
clearTimeout(mockVar.timeCounter);
mockVar.timeCounter = setTimeout(function(){startCounter(mockVar.time-1)},1000);
/* }else{
moveToFeedback();
//submit exam
}
*/ if(iOAP.noOptSec>0){
$('#noOptSec').html(iOAP.noOptSec);
$('#maxOptSec').html(iOAP.maxNoOptSec);
$("#showOptionalSecSummary").show();
}else{
$("#showOptionalSecSummary").hide();
}
}
function changeGroup(id){
if(mockVar.MaxGrpEnabled >= id && !mockVar.groups[mockVar.MaxGrpEnabled].isTypingGroup){
if((!mockVar.groups[id].isDisabled && mockVar.groups[id].isViewable=="Y")||mockVar.MaxGrpEnabled == id){
mockVar.currentGrp = id;
saveSAAlphanumeric();
fillGroups();
getQuestion();
if($('#typedAnswer')){
$('#typedAnswer').attr('disabled',true);
$('#finalTypingSub').attr('disabled',true);
$('#finalTypingSub').removeClass().addClass('typingTestButtonDisabled');
}
doCalculations(0,0);
numPanelSec();
fillSections();
enableOptButtons();
fillNumberPanel();
if(iOAP.noOptSec>0){
$('#noOptSec').html(iOAP.noOptSec);
$('#maxOptSec').html(iOAP.maxNoOptSec);
$("#showOptionalSecSummary").show();
}else{
$("#showOptionalSecSummary").hide();
}
}else{
var str = "<br/><center>You have already attempted "+mockVar.groups[id].groupName+" group. Viewing or editing this group is not allowed</center>";
str += "<table class='bordertable' cellspacing=0 width='80%' align='center' style='margin-top:10px'>";
str += "<tr><th>Section Name</th><th>No. of Questions</th><th>Answered</th><th>Not Answered</th><th>Marked for Review</th><th>Not Visited</th></tr>";
var temp_iOAP = mockVar.groups[id];
var noOfAns = 0,noOfNtAns=0,noOfReview=0,totalQues=0,noOfNtAttemp=0;
for(var i=1;i<temp_iOAP.secDetails.length;i++){
if(temp_iOAP.secDetails[i].isOptional=='N'){
str += "<tr><td>"+temp_iOAP.secDetails[i].secName+"</td><td>"+(temp_iOAP.sections[i][1].length-1)+"</td><td>"+temp_iOAP.secDetails[i].answered+"</td><td>"+temp_iOAP.secDetails[i].notanswered+"</td><td>"+temp_iOAP.secDetails[i].marked+"</td><td>"+(temp_iOAP.sections[i][1].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked-1)+"</td></tr>";
noOfAns = noOfAns + temp_iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + temp_iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + temp_iOAP.secDetails[i].marked;
totalQues = totalQues + temp_iOAP.sections[i][1].length-1;
noOfNtAttemp = noOfNtAttemp + temp_iOAP.sections[i][1].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked-1;
}else if(temp_iOAP.secDetails[i].isOptional=='Y' && temp_iOAP.secDetails[i].isSelected){
noOfAns = noOfAns + temp_iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + temp_iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + temp_iOAP.secDetails[i].marked;
totalQues = totalQues + temp_iOAP.sections[i][1].length-1;
noOfNtAttemp = noOfNtAttemp + temp_iOAP.sections[i][1].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked -1;
str += "<tr><td>"+temp_iOAP.secDetails[i].secName+"</td><td>"+(temp_iOAP.sections[i][1].length-1)+"</td><td>"+temp_iOAP.secDetails[i].answered+"</td><td>"+temp_iOAP.secDetails[i].notanswered+"</td><td>"+temp_iOAP.secDetails[i].marked+"</td><td>"+(temp_iOAP.sections[i][1].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked-1)+"</td></tr>";
}
}
str += "</table>"
$("#viewQPDiv").html(str);
showModule('QPDiv');
//alert("BMJ");
}
}
}
function fillSections(){
fillGroups();
var str="<table width='100%'>";
for(i=1;i< iOAP.secDetails.length ;i++){
var answeredQuestions = iOAP.secDetails[i].answered;
var notAnsweredQuestions = iOAP.secDetails[i].notanswered;
var markedQuestions = iOAP.secDetails[i].marked;
var langId = 0;
for(var k=1;k<iOAP.languages.length;k++){
if(iOAP.languages[k]!=null || typeof(iOAP.languages[k])!='undefined'){
langId=k;
break;
}
}
var noOfQuestions = iOAP.sections[i][langId].length;
var notAttemptedQuestions = noOfQuestions - markedQuestions - notAnsweredQuestions - answeredQuestions-1;
if(i%5==1){
str+="</td></tr>";
str+="<tr><td>";
}
str+='<div class="allSections" id="s'+i+'" ><a href="#" class="tooltip">';
str+='<div style="text-overflow:ellipsis;width:90%;overflow:hidden;white-space:nowrap;padding-left:10px;cursor:pointer">';
if(iOAP.secDetails[i].isOptional == 'Y'){
str += '<input name="optSec" id="opt'+i+'"';
if(iOAP.secDetails[i].isSelected == true){
str += ' checked ';
}
if(mockVar.currentGrp != mockVar.MaxGrpEnabled && mockVar.groups[mockVar.currentGrp].isEditable == "N"){
str += " disabled ";
}
str += 'type="checkbox"></input>';
}
str += iOAP.secDetails[i].secName+'</div>';
str += '<span class="classic"><center><table width="95%" style="font-size:14px;margin-top:10px" class="question_area" cellspacing="0">';
str += '<tr><td colspan="2"><b>'+iOAP.secDetails[i].secName+'</b></td></tr>';
str += '<tr><td colspan="2"><hr/></td></tr></table>';
str += '<table width="95%" style="margin-top:0%" class="question_area" cellspacing="5">';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Answered: </td><td valign="top"><span id="tooltip_answered">'+answeredQuestions+'</span></td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Not Answered: </td><td valign="top"><span id="tooltip_not_answered">'+notAnsweredQuestions+'</span></td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Marked for Review: </td><td valign="top"><span id="tooltip_review">'+markedQuestions+'</span></td></tr>';
str += '<tr><td style="text-align:left;padding-top:10px" width="80%">Not Visited: </td><td valign="top"><span id="tooltip_not_visited">'+notAttemptedQuestions+'</span></td></tr>';
str += '</table></center></span>';
str += '</a></div>';
}
str +="</td></tr></table>";
$('#sections').html(str);
//align
if( iOAP.secDetails.length>4 && $.browser.msie ){
for(var i=4;i<iOAP.secDetails.length;i=i+5){
$('#s'+(i+1)+" .tooltip").hover(
function(){ $(this).find(".classic").css({"margin-left":"-60px"});}
, function(){$(this).find(".classic").css({"margin-left":"-999px"});});
}
}
$("#s"+iOAP.curSection).addClass("currentSectionSelected");
$("#s"+iOAP.curSection+" a").addClass("tooltipSelected");
$("#sections .allSections input").click(function(event){
if(this.checked){
optSecCheck(this.id.split("opt")[1],event);
}
else{
optSecUncheck(this.id.split("opt")[1],event);
}
});
$("#sections .allSections").click(function (event){
if(event.target.type!="checkbox"){
changeSection(this.id.split("s")[1]);
}
});
}
function optSecCheck(secId,event){
var optSections = document.getElementsByName("optSec");
var counter = 0;
for(i=1;i<iOAP.secDetails.length;i++){
if(iOAP.secDetails[i].isOptional=='Y' && iOAP.secDetails[i].isSelected){
counter++;
}
}
counter++;
if(counter>iOAP.maxNoOptSec){
event.preventDefault();
if(event.stopPropagation){
event.stopPropagation();
}else
event.returnValue=false;
secChangeConfirmation();
}else{
iOAP.secDetails[secId].isSelected = true;
enableOptButtons();
changeSection(secId);
}
}
function optSecUncheck(secId,event){
event.preventDefault();
if(event.stopPropagation){
event.stopPropagation();
}else{
event.returnValue=false;
}
var str= "";
str ="<center><p style='margin-top:5%'><i>Deselecting the checkbox will DELETE all the options marked in this section. Do you want to continue?</i></p><br/>";
str +="<table align='center' style='margin-top:5%'>";
str +='<tr><td style="text-align:center"><input onclick="resetSection('+secId+');afterResetSection();" type="button" class="button" value="Reset"/></td><td style="text-align:center"><input onclick="showModule(';
str +="'questionCont'";
str +=')" type="button" class="button" value="Back"/></td></tr></table></center>';
$("#sectionSummaryDiv").html(str);
showModule('sectionSummaryDiv');
}
function resetSection(secId){
var counter = 0, langIdCount=0;
for(var langId=1;langId<iOAP.languages.length;langId++){
if(iOAP.languages[langId]!=null || typeof(iOAP.languages[langId])!='undefined'){
langIdCount++;
for(var j=1;j<iOAP.sections[secId][langId].length;j++){
iOAP.sections[secId][langId][j].answer = '';
iOAP.sections[secId][langId][j].typedWord = '';
if(iOAP.viewLang[secId][j].status != 'notAttempted'){
iOAP.viewLang[secId][j].status="notanswered";
counter++;
}
}
}
}
$('#answer').val('');
$("#noOfWords").text('');
iOAP.secDetails[secId].answered = 0;
//we are dividing here because the counter counts in all the languages.
iOAP.secDetails[secId].notanswered = counter/(langIdCount);
iOAP.secDetails[secId].marked = 0;
iOAP.secDetails[secId].isSelected = false;
}
function afterResetSection(){
showModule('questionCont');
fillSections();
enableOptButtons();
fillNumberPanel();
}
function enableOptButtons(){
$("#savenext").removeAttr("title");
$("#underreview").removeAttr("title");
$("#clearResponse").removeAttr("title");
$("#savenext").removeAttr("disabled");
$("#underreview").removeAttr("disabled");
$("#clearResponse").removeAttr("disabled");
if(mockVar.currentGrp == mockVar.MaxGrpEnabled){
if(iOAP.secDetails[iOAP.curSection].isOptional == 'Y' && !iOAP.secDetails[iOAP.curSection].isSelected){
$("#savenext").attr("title","Select the section to attempt this question");
$("#underreview").attr("title","Select the section to attempt this question");
$("#clearResponse").attr("title","Select the section to attempt this question");
$("#savenext").attr("disabled","disabled");
$("#underreview").attr("disabled","disabled");
$("#clearResponse").attr("disabled","disabled");
}
}else if(mockVar.groups[mockVar.currentGrp].isEditable == "N"){
$("#savenext").attr("title","Editing this group is not allowed");
$("#underreview").attr("title","Editing this group is not allowed");
$("#clearResponse").attr("title","Editing this group is not allowed");
$("#savenext").attr("disabled","disabled");
$("#underreview").attr("disabled","disabled");
$("#clearResponse").attr("disabled","disabled");
$('#answer').attr('disabled','disabled');
}
if($("#savenext").attr('disabled')){
$("#savenext").removeClass().addClass('btnDisabled');
}else{
$("#savenext").removeClass().addClass('btnEnabled');
}
}
function secChangeConfirmation(){
var quesStatus;
var noOfAns=0,noOfNtAns=0,noOfReview=0,noOfNtAttemp=0,totalQues=0, langId=0;
var str= "";
for(var k=1;k<iOAP.languages.length;k++){
if(iOAP.languages[k]!=null || typeof(iOAP.languages[k])!='undefined'){
langId=k;
break;
}
}
str +="<center><p style='margin-top:5%;width:75%;text-align:left'><font color='red'>WARNING!<br/> You can attempt only "+iOAP.maxNoOptSec+" of the "+(iOAP.noOptSec)+" optional section(s).";
str += "You have chosen to change the optional section. You are required to reset the previously chosen optional sections by clicking on the corresponding ";
str += "checkbox in the table given below and then clicking on the RESET button. Please be aware that by resetting one of the previously chosen optional ";
str += "Sections, all the answers you have provided for questions in that Section will be DELETED. (If you choose to come back to this section ";
str += "later, you will have to start answering this Section afresh. Hence if you think you will need to come back to this Section later, then ";
str += "you need to note down your answers on the Scribble Pad provided.). Are you sure that you want to reset an optional section now?";
str += "<br/><br/>If YES, check the checkbox next to section you wish to reset and click on <b>Reset</b> button to reset that section. </font></p>";
str += "<center><b>Summary of Optional Section(s) : </b></center>";
str += "<table class='bordertable' cellspacing=0 width='60%' align='center' >";
str += "<tr><th>Optional Section Name</th><th>No. of Questions</th><th>Answered</th><th>Not Answered</th><th>Marked for Review</th><th>Not Visited</th><th> Reset </th></tr>";
for(var i=1;i<iOAP.secDetails.length;i++){
if(iOAP.secDetails[i].isOptional=='Y'){
if(iOAP.secDetails[i].isSelected){
str += "<tr><td>"+iOAP.secDetails[i].secName+"</td><td>"+(iOAP.sections[i][langId].length-1)+"</td><td>"+iOAP.secDetails[i].answered+"</td><td>"+iOAP.secDetails[i].notanswered+"</td><td>"+iOAP.secDetails[i].marked+"</td><td>"+(iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1)+"</td><td><input type='checkbox' ";
str += " value="+i+" name='confSec'/></td></tr>";
}
}
}
str += "</table></center>";
str +="<center><table align='center' style='margin-top:1%' ><tr><td colspan=2 id='errorMsg'>&nbsp;</td></tr>";
str +='<tr><td style="text-align:center"><input onclick="confirmChangeSec()" type="button" class="button" value="Reset"/></td><td style="text-align:center"><input onclick="showModule(';
str +="'questionCont'";
str +=')" type="button" class="button" value="Back"/></td></tr></table></center>';
$("#sectionSummaryDiv").html(str);
showModule('sectionSummaryDiv');
}
function finalSecChangeConf(secIds){
if($.trim(secIds) != ""){
var sections = secIds.split(",");
for(var i = 0;i<sections.length-1;i++){
resetSection(sections[i]);
}
}
afterResetSection();
}
function confirmChangeSec(){
var allCheckedSections = document.getElementsByName("confSec");
var secIds = "";
for(var i = 0;i<allCheckedSections.length;i++){
if(allCheckedSections[i].checked)
secIds += allCheckedSections[i].value+","
}
var sections = secIds.split(',');
if(sections.length>1){
var str = "";
str = "<center><table cellspacing=0 width='60%' align='center' style='margin-top:5%'>";
str += "<tr><td colspan=2 style='text-align:center'>Resetting this section will DELETE your responses to all the questions in this section. Are you sure you want to reset section ";
for(var i =0 ; i<sections.length-1 ; i++){
//console.log(sections[i]);
str += iOAP.secDetails[sections[i]].secName +" ,";
}
str = str.substring(0,str.length-2);
str += " ?</td></tr><tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2>&nbsp;</td></tr>";
str +='<tr><td style="text-align:right;margin-right:5px"><input onclick="finalSecChangeConf(';
str += "'"+secIds+"'";
str += ')" type="button" class="button" value="Reset"/></td><td style="text-align:left;margin-left:5px"><input onclick="showModule(';
str +="'questionCont'";
str +=')" type="button" class="button" value="Back"/></td></tr></table></center>';
$("#sectionSummaryDiv").html(str);
}else{
$('#errorMsg').html('<center><font style="color:red;font-weight:bold">Please select the section(s) to reset</font></center>');
}
}
function changeSection (sectionID){
saveSAAlphanumeric();
if(sectionID!=iOAP.curSection){
iOAP.secDetails[iOAP.curSection].curQues = iOAP.curQues;
iOAP.curQues = iOAP.secDetails[sectionID].curQues;
iOAP.curSection = sectionID;
}
enableOptButtons();
getQuestion();
changeQues(iOAP.curQues);
numPanelSec();
fillNumberPanel();
}
function fillNumberPanel(){
var quesStatus;
var str = '<center><table style="margin-top:-2%;" cellspacing="0" class="question_area " cellpadding="0" border="0" valign="top"><tr>';
for(var i=1;i<iOAP.viewLang[iOAP.curSection].length;i++){
if(i%4==1){
str+='</tr>';
str+='<tr>';
}
quesStatus=iOAP.viewLang[iOAP.curSection][i].status ;
if(quesStatus=="answered"){
str+='<td id="qtd'+i+'"><span title ="Answered" class="answered" id="'+i+'" onclick="javascript:changeQues('+i+');"> '+i+'</span></td>';
}else if(quesStatus=="notanswered"){
str+='<td id="qtd'+i+'"><span title ="Not Answered" class="not_answered" id="'+i+'" onclick="javascript:changeQues('+i+');"> '+i+'</span></td>';
}else if(quesStatus=="marked"){
if(iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][i].langID][i].answer == null || iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][i].langID][i].answer == ''){
str+='<td id="qtd'+i+'"><span title ="Marked & Not Answered" class="review" id="'+i+'" onclick="javascript:changeQues('+i+');"> '+i+'</span></td>';
}else{
str+='<td id="qtd'+i+'"><span title ="Marked & Answered" class="review_answered" id="'+i+'" onclick="javascript:changeQues('+i+');"> '+i+'</span></td>';
}
}
else{
str+='<td id="qtd'+i+'"><span title ="Not attempted" class="not_visited" id="'+i+'" onclick="javascript:changeQues('+i+');"> '+i+'</span></td>';
}
}
str+='</tr></TBODY></table></center>';
$('#numberpanelQues').html(str);
}
function changeQues(quesID){
saveSAAlphanumeric();
removeActiveLinks();
iOAP.curQues = quesID;
showModule("questionCont");
getQuestion();
if($('#typedAnswer')){
$('#typedAnswer').attr('disabled',true);
$('#finalTypingSub').attr('disabled',true);
$('#finalTypingSub').removeClass().addClass('typingTestButtonDisabled');
}
doCalculations(0,0);
fillNumberPanel();
}
function showModule(moduleName){
for(var i=0;i<iOAP.modules.length;i++){
if(mockVar.modules[i]==moduleName){
$("#"+mockVar.modules[i]).show();
}else{
$("#"+mockVar.modules[i]).hide();
}
}
focusOnDiv();
}
function numPanelSec(){
$('#viewSection').html('You are viewing <b>'+iOAP.secDetails[iOAP.curSection].secName+ '</b> section ');
}
function resetOption(){
var ques = iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues];
iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues].answer = '';
if(ques.quesType =="SA" || ques.quesType =="COMPREHENSION@@SA" || ques.quesType =="LA@@SA" ){
$('#answer').val('');
$("#noOfWords").text('');
}else{
var answers = document.getElementsByName('answers');
for(i=0;i<answers.length;i++)
{
if(answers[i].checked==true)
answers[i].checked=false;
}
}
fnSubmit('RESET');
}
function saveSAAlphanumeric(){
var ques=iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues];
if((ques.quesType =="SA" || ques.quesType =="COMPREHENSION@@SA" || ques.quesType =="LA@@SA") && ques.keyboardType == 2){
fnSubmit('SAVESA');
}
}
function submitConfirmation(param){
var quesStatus;
var ques=iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues];
var noOfAns=0,noOfNtAns=0,noOfReview=0,noOfNtAttemp=0,totalQues=0;
var wrongCharCount = 0, wrongWordCount = 0, grossWordCount = 0, netWordCount = 0, ellapsedTime = 0, grossWordPerMin = 0, netWordPerMin = 0, accuracy = 0;
var str= "";
var langId = 0;
for(var k=1;k<iOAP.languages.length;k++){
if(iOAP.languages[k]!=null || typeof(iOAP.languages[k])!='undefined'){
langId=k;
break;
}
}
saveSAAlphanumeric();
str = "<center><h3><b>Exam Summary</b></h3>";
if(mockVar.groups.length==1){
str += "<table class='bordertable' cellspacing=0 width='80%' align='center' style='margin-top:5%'>";
if(iOAP.secDetails[1].secType == 'TYPING'){
str += "<tr><th>Section Name</th><th>Gross KeyStrokes Count</th><th>Backspace Count</th><th>Elapsed Time (In Mins)</th></tr>";
}else{
str += "<tr><th>Section Name</th><th>No. of Questions</th><th>Answered</th><th>Not Answered</th><th>Marked for Review</th><th>Not Visited</th></tr>";
}
for(var i=1;i<iOAP.secDetails.length;i++){
if(iOAP.secDetails[i].secType == 'TYPING'){
wrongCharCount = (ques.typingType==1)?0:getWrongCharCount();
wrongWordCount = wrongCharCount/5;
grossWordCount = (mockVar.typingGroup[mockVar.currentGrp].keyStrokesCount - mockVar.typingGroup[mockVar.currentGrp].backSpaceCount)/5;
netWordCount = (grossWordCount - wrongWordCount);
ellapsedTime = mockVar.groups[0].ellapsedTime;
grossWordPerMin = (grossWordCount/ellapsedTime)*60;
netWordPerMin = (netWordCount/ellapsedTime)*60;
iOAP.secDetails[i].GWPM = grossWordPerMin;
iOAP.secDetails[i].NWPM = netWordPerMin;
accuracy = (iOAP.secDetails[i].GWPM/iOAP.secDetails[i].NWPM)*100;
str += "<tr><td width='25%'>"+iOAP.secDetails[i].secName+"</td><td width='25%'>"+mockVar.typingGroup[mockVar.currentGrp].keyStrokesCount+"</td><td width='25%'>"+mockVar.typingGroup[mockVar.currentGrp].backSpaceCount+"</td><td width='25%'>"+(ellapsedTime/60).toFixed(2)+"</td></tr>";
}else{
if(iOAP.secDetails[i].isOptional=='N'){
str += "<tr><td width='25%'>"+iOAP.secDetails[i].secName+"</td><td width='15%'>"+(iOAP.sections[i][1].length-1)+"</td><td width='15%'>"+iOAP.secDetails[i].answered+"</td><td width='15%'>"+iOAP.secDetails[i].notanswered+"</td><td width='15%'>"+iOAP.secDetails[i].marked+"</td><td width='15%'>"+(iOAP.sections[i][1].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1)+"</td></tr>";
noOfAns = noOfAns + iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + iOAP.secDetails[i].marked;
totalQues = totalQues + iOAP.sections[i][langId].length-1;
noOfNtAttemp = noOfNtAttemp + iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1;
}else if(iOAP.secDetails[i].isOptional=='Y' && iOAP.secDetails[i].isSelected){
noOfAns = noOfAns + iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + iOAP.secDetails[i].marked;
totalQues = totalQues + iOAP.sections[i][langId].length-1;
noOfNtAttemp = noOfNtAttemp + iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked -1;
str += "<tr><td>"+iOAP.secDetails[i].secName+"</td><td>"+(iOAP.sections[i][langId].length-1)+"</td><td>"+iOAP.secDetails[i].answered+"</td><td>"+iOAP.secDetails[i].notanswered+"</td><td>"+iOAP.secDetails[i].marked+"</td><td>"+(iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1)+"</td></tr>";
}
}
}
if(iOAP.secDetails.length>2){
str +="<tr><td><b>Total</b></td><td><b>"+totalQues+"</b></td><td><b>"+noOfAns+"</b></td><td><b>"+noOfNtAns+"</b></td><td><b>"+noOfReview+"</b></td><td><b>"+noOfNtAttemp+"</b></td></tr>";
}
str += "</table></center>";
}else{
str += "<div id='group_summary' style='width:90%;overflow:auto;text-align:left'>";
str += "<span style='margin-left:10%'><b>"+mockVar.groups[mockVar.currentGrp].groupName+"</b> : ( Current Group )</span>";
str += "<table class='bordertable' cellspacing=0 width='80%' align='center'>";
if(iOAP.secDetails[1].secType == 'TYPING'){
str += "<tr><th>Section Name</th><th>Gross KeyStrokes Count</th><th>Backspace Count</th><th>Elapsed Time (In Mins)</th></tr>";
}else{
str += "<tr><th>Section Name</th><th>No. of Questions</th><th>Answered</th><th>Not Answered</th><th>Marked for Review</th><th>Not Visited</th></tr>";
}
for(var i=1;i<iOAP.secDetails.length;i++){
if(iOAP.secDetails[i].secType == 'TYPING'){
wrongCharCount = (ques.typingType==1)?0:getWrongCharCount();
wrongWordCount = wrongCharCount/5;
grossWordCount = (mockVar.typingGroup[mockVar.currentGrp].keyStrokesCount - mockVar.typingGroup[mockVar.currentGrp].backSpaceCount)/5;
netWordCount = (grossWordCount - wrongWordCount);
ellapsedTime = mockVar.groups[mockVar.currentGrp].ellapsedTime;
grossWordPerMin = (grossWordCount/ellapsedTime)*60;
netWordPerMin = (netWordCount/ellapsedTime)*60;
iOAP.secDetails[i].GWPM = grossWordPerMin;
iOAP.secDetails[i].NWPM = netWordPerMin;
accuracy = (iOAP.secDetails[i].NWPM/iOAP.secDetails[i].GWPM)*100;
str += "<tr><td width='25%'>"+iOAP.secDetails[i].secName+"</td><td width='25%'>"+mockVar.typingGroup[mockVar.currentGrp].keyStrokesCount+"</td><td width='25%'>"+mockVar.typingGroup[mockVar.currentGrp].backSpaceCount+"</td><td width='25%'>"+(ellapsedTime/60).toFixed(2)+"</td></tr>";
}else{
if(iOAP.secDetails[i].isOptional=='N'){
str += "<tr><td width='25%'>"+iOAP.secDetails[i].secName+"</td><td width='15%'>"+(iOAP.sections[i][langId].length-1)+"</td><td width='15%'>"+iOAP.secDetails[i].answered+"</td><td width='15%'>"+iOAP.secDetails[i].notanswered+"</td><td width='15%'>"+iOAP.secDetails[i].marked+"</td><td width='15%'>"+(iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1)+"</td></tr>";
noOfAns = noOfAns + iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + iOAP.secDetails[i].marked;
totalQues = totalQues + iOAP.sections[i][langId].length-1;
noOfNtAttemp = noOfNtAttemp + iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1;
}else if(iOAP.secDetails[i].isOptional=='Y' && iOAP.secDetails[i].isSelected){
noOfAns = noOfAns + iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + iOAP.secDetails[i].marked;
totalQues = totalQues + iOAP.sections[i][langId].length-1;
noOfNtAttemp = noOfNtAttemp + iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked -1;
str += "<tr><td width='25%'>"+iOAP.secDetails[i].secName+"</td><td width='15%'>"+(iOAP.sections[i][langId].length-1)+"</td><td width='15%'>"+iOAP.secDetails[i].answered+"</td><td width='15%'>"+iOAP.secDetails[i].notanswered+"</td><td width='15%'>"+iOAP.secDetails[i].marked+"</td><td width='15%'>"+(iOAP.sections[i][langId].length-iOAP.secDetails[i].answered-iOAP.secDetails[i].notanswered-iOAP.secDetails[i].marked-1)+"</td></tr>";
}
}
}
str += "</table>";
for(var j=0;j<mockVar.groups.length;j++){
var temp_iOAP = mockVar.groups[j];
if(mockVar.currentGrp >j){
str += "<br/><span style='margin-left:10%'><b>"+mockVar.groups[j].groupName+"</b> : ( Attempted Group ";
if(mockVar.groups[j].isViewable=="Y"){
str += "; Can View";
}else{
str += "; Cannot View";
}
if(mockVar.groups[j].isEditable=="Y"){
str += "; Can Edit";
}else{
str += "; Cannot Edit";
}
str += ")</span>";
str += "<table class='bordertable' cellspacing=0 width='80%' align='center'>";
if(temp_iOAP.secDetails[1].secType == 'TYPING'){
str += "<tr><th>Section Name</th><th>Gross KeyStrokes Count</th><th>Backspace Count</th><th>Elapsed Time (In Mins)</th></tr>";
}else{
str += "<tr><th>Section Name</th><th>No. of Questions</th><th>Answered</th><th>Not Answered</th><th>Marked for Review</th><th>Not Visited</th></tr>";
}
for(var i=1;i<temp_iOAP.secDetails.length;i++){
if(temp_iOAP.secDetails[i].secType == 'TYPING'){
accuracy = (temp_iOAP.secDetails[i].NWPM/temp_iOAP.secDetails[i].GWPM)*100;
str += "<tr><td width='25%'>"+temp_iOAP.secDetails[i].secName+"</td><td width='25%'>"+mockVar.typingGroup[j].keyStrokesCount+"</td><td width='25%'>"+mockVar.typingGroup[j].backSpaceCount+"</td><td width='25%'>"+(mockVar.groups[j].ellapsedTime/60).toFixed(2)+"</td></tr>";
}else{
if(temp_iOAP.secDetails[i].isOptional=='N'){
str += "<tr><td width='25%'>"+temp_iOAP.secDetails[i].secName+"</td><td width='15%'>"+(temp_iOAP.sections[i][langId].length-1)+"</td><td width='15%'>"+temp_iOAP.secDetails[i].answered+"</td><td width='15%'>"+temp_iOAP.secDetails[i].notanswered+"</td><td width='15%'>"+temp_iOAP.secDetails[i].marked+"</td><td width='15%'>"+(temp_iOAP.sections[i][langId].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked-1)+"</td></tr>";
noOfAns = noOfAns + temp_iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + temp_iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + temp_iOAP.secDetails[i].marked;
totalQues = totalQues + temp_iOAP.sections[i][langId].length-1;
noOfNtAttemp = noOfNtAttemp + temp_iOAP.sections[i][langId].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked-1;
}else if(temp_iOAP.secDetails[i].isOptional=='Y' && temp_iOAP.secDetails[i].isSelected){
noOfAns = noOfAns + temp_iOAP.secDetails[i].answered;
noOfNtAns = noOfNtAns + temp_iOAP.secDetails[i].notanswered;
noOfReview = noOfReview + temp_iOAP.secDetails[i].marked;
totalQues = totalQues + temp_iOAP.sections[i][langId].length-1;
noOfNtAttemp = noOfNtAttemp + temp_iOAP.sections[i][langId].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked -1;
str += "<tr><td width='25%'>"+temp_iOAP.secDetails[i].secName+"</td><td width='15%'>"+(temp_iOAP.sections[i][langId].length-1)+"</td><td width='15%'>"+temp_iOAP.secDetails[i].answered+"</td><td width='15%'>"+temp_iOAP.secDetails[i].notanswered+"</td><td width='15%'>"+temp_iOAP.secDetails[i].marked+"</td><td width='15%'>"+(temp_iOAP.sections[i][langId].length-temp_iOAP.secDetails[i].answered-temp_iOAP.secDetails[i].notanswered-temp_iOAP.secDetails[i].marked-1)+"</td></tr>";
}
}
}
str += "</table>";
}else if(mockVar.currentGrp<j){
str += "<br/><br/><span style='margin-left:10%'><b>"+mockVar.groups[j].groupName+"</b> : ( Yet to attempt )</span>";
}
}
/*if(iOAP.secDetails.length>2){
str +="<tr><td><b>Total</b></td><td><b>"+totalQues+"</b></td><td><b>"+noOfAns+"</b></td><td><b>"+noOfNtAns+"</b></td><td><b>"+noOfReview+"</b></td><td><b>"+noOfNtAttemp+"</b></td></tr>";
}*/
str += "</div></center>";
}
if(param == 'submit'){
str +="<center><table align='center' id='confirmation_buttons' style='margin-top:5%'><tr><td colspan='2'>Are you sure you want to submit ";
if(mockVar.groups.length<=1)
str += "the Exam";
else
str += mockVar.groups[mockVar.currentGrp].groupName+' group';
str +=' ?</td></tr><tr><td style="text-align:center"><input onclick="finalSubmit(';
str += "'group'";
str +=')" type="button" class="button" style="width:50px" value="Yes"/></td><td style="text-align:center"><input onclick="showModule(';
str +="'questionCont'";
str +=');doCalculations(0,0);removeActiveLinks();" type="button" class="button" style="width:50px" value="No"/></td></tr></table></center>';
}else if(param == 'timeout'){
str += "<center><input onclick='moveToFeedback()' type='button' class='button' value='Next'/></center>";
}else if(param == 'break'){
$('#questionContent').hide();
$("#breakSummaryDiv").html(str);
$('#breakTimeDiv').show();
}
$("#sectionSummaryDiv").html(str);
showModule('sectionSummaryDiv');
$("#group_summary").css({"height":($(document).height()*.40)+"px"});
}
function moveToFeedback(){
if(mockVar.isFeedBackRequired == "NO"){
window.location.href = "close.html?"+mockVar.orgId +"@@"+mockVar.mockId+"#";
}else{
window.location.href = "FeedBack.html?"+mockVar.orgId +"@@"+mockVar.mockId+"#";
}
}
function finalSubmit(type){
var str ="<center><table style='margin-top:5%'><tr><td colspan='2'>Are you sure you want to submit ";
if(mockVar.groups.length<=1)
str += "the Exam";
else
str += mockVar.groups[mockVar.currentGrp].groupName+" group";
str +=' ?</td></tr><tr><td style="text-align:center"><input onclick="';
if(type=="submit"){
str += 'fnSubmit(';
str += "'SUBMIT'";
str += ')"';
}else{
str +="checkGroupBreakTime();removeActiveLinks();";
}
str += '" type="button" class="button" value="Yes" style="width:50px"/></td><td style="text-align:center"><input onclick="showModule(';
str +="'questionCont'";
str +=');doCalculations(0,0);removeActiveLinks();" type="button" class="button" value="No" style="width:50px"/></td></tr></table></center>';
$("#sectionSummaryDiv").html(str);
}
function fnSubmit(action){
var ques=iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues];
var selectedAnswer="";
var wordCountForSA = "";
var proceed = true;
var section = iOAP.secDetails[iOAP.curSection];
var quesToBeConsidered = parseInt(section.answered);
if(action != "SKIP"){
if(ques.quesType =="SA" || ques.quesType =="COMPREHENSION@@SA" || ques.quesType =="LA@@SA"){
selectedAnswer = document.getElementById('answer').value;
wordCountForSA = $("#noOfWords").text();
}else if(ques.quesType =="TYPING"){
selectedAnswer = document.getElementById('typedAnswer').value;
}else if(ques.quesType != "SUBJECTIVE"){
var answers = document.getElementsByName('answers');
for(var i=0;i<answers.length;i++)
{
if(answers[i].checked==true)
{
selectedAnswer=answers[i].value + "," + selectedAnswer;
}
}
if(selectedAnswer !="")
selectedAnswer = selectedAnswer.substring(0,selectedAnswer.length-1);
}
}
if(section.maxOptQuesToAns != ""){
if(mockVar.isMarkedForReviewConsidered == "YES"){
var counter = 0;
for(i=1;i<iOAP.viewLang[iOAP.curSection].length;i++){
var quesStatus=iOAP.viewLang[iOAP.curSection][i].status ;
if(quesStatus=="marked" && !(iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][i].langID][i].answer == null
|| iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][i].langID][i].answer == '')){
counter++;
}
}
quesToBeConsidered += counter;
}
var curQuesStatus = iOAP.viewLang[iOAP.curSection][iOAP.curQues].status;
if(!(action=="SKIP" || action=="RESET" || action=="SUBMIT") &&
!(curQuesStatus=="answered" || (curQuesStatus == "marked" &&
iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID][iOAP.curQues].answer!=""))){
if(quesToBeConsidered==section.maxOptQuesToAns && selectedAnswer!="" ){
proceed = false;
}
}
}
if(proceed){
for(i=1;i<iOAP.languages.length;i++){
if(iOAP.languages[i]!=null || typeof(iOAP.languages[i])!='undefined'){
iOAP.sections[iOAP.curSection][i][iOAP.curQues].answer = selectedAnswer;
iOAP.sections[iOAP.curSection][i][iOAP.curQues].typedWord = wordCountForSA;
}
}
if(action!='SUBMIT')
{
save(selectedAnswer, action,ques.quesType);
}
else
{
moveToFeedback();
}
}else{
fillMaxOptQuesCrossed(quesToBeConsidered,iOAP.viewLang[iOAP.curSection].length-1);
showModule('sectionSummaryDiv');
}
}
function fillMaxOptQuesCrossed(quesToBeConsidered,totalQuestions){
var str= "";
str ="<center><div style='width:50%'><p style='margin-top:5%'><i>You have attemped "+quesToBeConsidered+" out of "+totalQuestions+", which is the maximum limit of this section. If you wish to answer this question you need to reset the questions which are already answered ";
if(mockVar.isMarkedForReviewConsidered == "YES"){
str += " or marked for review and answered";
}
str +="</i></p><br/>";
str +="<table align='center' style='margin-top:5%'>";
str +='<tr><td style="text-align:center"><input onclick="showModule(';
str +="'questionCont'";
str +=')" type="button" class="button" value="Back"/></td></tr></table></div></center>';
$("#sectionSummaryDiv").html(str);
}
function save(ansID, action,quesType){
var quesStatus = iOAP.viewLang[iOAP.curSection][iOAP.curQues].status;
var sec = iOAP.secDetails[iOAP.curSection];
if(ansID == "" && quesType != "SUBJECTIVE")
ansID = null;
if(action=="MARK"){
if(quesStatus=="answered"){
iOAP.secDetails[iOAP.curSection].answered--;
}else if(quesStatus=="notanswered"){
iOAP.secDetails[iOAP.curSection].notanswered--;
}
if(quesStatus!="marked"){
iOAP.secDetails[iOAP.curSection].marked++;
}
iOAP.viewLang[iOAP.curSection][iOAP.curQues].status="marked";
}else if(action=="RESET"){
if(quesStatus=="marked"){
iOAP.secDetails[iOAP.curSection].marked--;
iOAP.secDetails[iOAP.curSection].notanswered++;
}else if(quesStatus=="answered"){
iOAP.secDetails[iOAP.curSection].answered--;
iOAP.secDetails[iOAP.curSection].notanswered++;
}
iOAP.viewLang[iOAP.curSection][iOAP.curQues].status="notanswered";
}else if(action=="NEXT" || action == "SAVESA"){
if(ansID==null){
if(quesStatus=="answered"){
iOAP.secDetails[iOAP.curSection].notanswered++;
iOAP.secDetails[iOAP.curSection].answered--;
}else if(quesStatus=="marked"){
iOAP.secDetails[iOAP.curSection].notanswered++;
iOAP.secDetails[iOAP.curSection].marked--;
}
iOAP.viewLang[iOAP.curSection][iOAP.curQues].status="notanswered";
}else{
if(quesStatus!="answered"){
if(quesStatus=="marked")
iOAP.secDetails[iOAP.curSection].marked--;
if(quesStatus=="notanswered")
iOAP.secDetails[iOAP.curSection].notanswered--;
iOAP.secDetails[iOAP.curSection].answered++;
}
iOAP.viewLang[iOAP.curSection][iOAP.curQues].status="answered";
}
}
if(action=="NEXT" || action=="MARK" || action=="SKIP"){
var secQuesLength= iOAP.sections[iOAP.curSection][iOAP.viewLang[iOAP.curSection][iOAP.curQues].langID].length ;
if(iOAP.curQues<secQuesLength-1){
iOAP.curQues = iOAP.curQues + 1;
iOAP.secDetails[iOAP.curSection].curQues = iOAP.curQues;
getQuestion();
numPanelSec()
fillNumberPanel();
}
else{
if(iOAP.curSection==iOAP.sections.length-1){
iOAP.curSection = 1;
}else{
iOAP.curSection++;
}
iOAP.curQues = 1;
getQuestion();
numPanelSec()
fillNumberPanel();
}
}else{
getQuestion();
numPanelSec()
fillNumberPanel();
}
}
function bConfirm(){
$('#pWait').css({"background":"none","opacity":"1","width":"99%","height":"98%"});
var str = '<div style="top:40%;left:30%;width:400px;position:relative;background:white;border:2px solid #000"><h1 id="popup_title" class="confirm"></h1>';
str += '<div id="popup_content" class="confirm">'+
'<div id="popup_message">You have reached the last question of the exam.Do you want to go to the first question again?</div>'+
'<div id="popup_panel">'+
'<input type="image" id="popup_ok" value="&nbsp;OK&nbsp;" onclick="bConfirmOK()" src="images/ok_new.gif">'+
'<input type="image" id="popup_cancel" value="&nbsp;Cancel&nbsp;" onclick="bConfirmCancel()" src="images/cancel_new.gif">'+
'</div>'+
'</div></div>';
$('#pWait').html(str);
$('#pWait').show();
}
function bConfirmOK(){
iOAP.curSection = 1;
iOAP.curQues = 1;
getQuestion();
numPanelSec();
fillNumberPanel();
$("#pWait").hide();
}
function bConfirmCancel(){
getQuestion();
numPanelSec();
fillNumberPanel();
$("#pWait").hide();
}
function timer(){
if(iOAP.secWiseTimer==0){
startCounter(mockVar.time);
}
}
function startCounter(time){
$("#showTime").html( "<b>Time Left : <span id='timeInMins'>"+convertTime(time)+"</span></b>");
if(mockVar.groups[mockVar.currentGrp].maxTime>0){
if(mockVar.groups[mockVar.currentGrp].maxTime - time >= mockVar.minSubmitTime && mockVar.currentGrp == mockVar.MaxGrpEnabled){
$("#finalSub").removeAttr("disabled");
}else{
$("#finalSub").attr("disabled","true");
}
}else{
if(mockVar.nonTimeBoundTime - time >= mockVar.minSubmitTime && mockVar.currentGrp == mockVar.MaxGrpEnabled){
$("#finalSub").removeAttr("disabled");
}else{
$("#finalSub").attr("disabled","true");
}
}
mockVar.time = time-1;
if(time<=300){
$('#timeInMins').css('color','red');
}
if(time>0){
mockVar.timeCounter = setTimeout(function(){startCounter(time-1)},1000);
}else{
mockVar.groups[mockVar.currentGrp].ellapsedTime = mockVar.groups[mockVar.currentGrp].maxTime; //required for typing group
if(mockVar.currentGrp < mockVar.groups.length-1 ){
checkGroupBreakTime();
//changeGroup(mockVar.currentGrp);
}else{
alert("Time out !!! Your answers have been saved successfully");
//window.location.href="FeedBack.html";
timeOutSubmit();
}
}
}
function breakTimeCounter(time){
$("#breakTimeCounter").html( "<b>Break Time Left : "+convertTime(time)+"</b>");
if(time>0){
mockVar.timeCounter = setTimeout(function(){breakTimeCounter(time-1)},1000);
}else{
submitGroup();
}
}
function timeOutSubmit(){
submitConfirmation('timeout');
$("#pWait").hide();
$("#sectionSummaryDiv").css({"height":"80%","border":"1px #fff solid"});
$("#groups").html('');
$('#groups').css({"border":"1px #fff solid"});
$('#sectionsField').html('');
$('#sectionsField').css({"border":"1px #fff solid"});
//$('#assessmentname').html('');
$('#timer').html('');
$('.numberpanel').html('');
$('.numberpanel').css({"background":"#fff","border-left":"1px #000 solid","height":"100%"});
$('.numberpanel').html('<div style="top:25%;position:relative"><center><img src="images/NewCandidateImage.jpg" width="50%" /> </center></div>');
}
function convertTime(time){
return showMin(time)+":"+showSec(time);
}
function showMin(time){
var min = "";
// time = time%3600;
min = parseInt(time/60);
return min;
}
function showSec(time){
var sec="";
if((time%60)>9)
sec = time%60;
else
sec = "0"+time%60;
return sec;
}
/* Time in hours
function convertTime(time){
return showHr(time)+":"+showMin(time)+":"+showSec(time);
}
function showHr(time){
return "0"+parseInt(time/3600);
}
function showMin(time){
var min = "";
time = time%3600;
if((time/60)>9)
min = parseInt(time/60);
else
min = "0"+parseInt(time/60);
return min;
}
function showSec(time){
var sec="";
if((time%60)>9)
sec = time%60;
else
sec = "0"+time%60;
return sec;
}
*/
function imgMagnifyInc( img,percentage){
var width = img.width;
var height = img.height;
height= height + height*percentage/100;
width = width+ width*percentage/100;
var zindex=1;
if(percentage>0)
zindex = 999;
$(img).css({"height":height,"width":width,"z-index":zindex,"position":"relative"});
}
function showQP(){
var i,j;
var str = "";
var noOfQues = new Array();
var quesCounter=0;
var counter =0;
var addQuesGroupCounter = false;
for(i=1;i<iOAP.viewLang.length;i++){
for(j=1;j<iOAP.viewLang[i].length;j++){
ques = iOAP.sections[i][iOAP.viewLang[i][j].langID][j];
if(ques.quesType.indexOf("@@") !=-1 ){
if(ques.isParent){
if(!addQuesGroupCounter){
addQuesGroupCounter = true;
}else{
noOfQues[quesCounter]= counter;
quesCounter++;
}
counter=1;
}else{
counter++;
}
}else{
if(counter>1){
noOfQues[quesCounter]= counter;
quesCounter++;
}
counter=1;
}
}
}
quesCounter=0;
if(mockVar.groups.length>1){
str+=str +="<h2><font color='#2F72B7'> "+mockVar.groups[mockVar.currentGrp].groupName+"</font></h2>" ;
}
for(i=1;i<iOAP.viewLang.length;i++){
str +="<h2><font color='#2F72B7'>Section : "+iOAP.secDetails[i].secName+"</font></h2>" ;
for(j=1;j<iOAP.viewLang[i].length;j++){
ques = iOAP.sections[i][iOAP.viewLang[i][j].langID][j];
if(ques.quesType.indexOf("@@") !=-1 ){
str += "<p style='padding-left:5px'>";
if(ques.isParent){
if(ques.quesType.split("@@")[0] == "COMPREHENSION" ){
str += "<b>"+mockVar.compQName ;
}
else if(ques.quesType.split("@@")[0] == "LA"){
str += "<b>"+mockVar.laQName ;
}
str += "(Question Number "+j+" to "+(j+noOfQues[quesCounter]-1)+") :</b> <br/> "+ques.quesText.split("@@&&")[0] + "<br/>";
quesCounter++;
}
str += "<table><tr><td valign='top' width='50px'>Q. "+j+") </td><td>"+ ques.quesText.split("@@&&")[1]+"</td>";
}else{
str += "<p style='padding-left:5px'><table><tr><td>Q. "+j+") </td><td>"+ ques.quesText+"</td></tr>";
}
str += "<tr><td width='50px'></td><td><i style='font-size:1em;'>";
if(ques.quesType.indexOf("MCQ")>-1 && mockVar.mcQName.length>0){
str += "Question Type : <b>";
str += mockVar.mcQName;
str += "</b>;";
}else if(ques.quesType.indexOf("MSQ")>-1 && mockVar.msQName.length > 0){
str += "Question Type : <b>";
str += mockVar.msQName;
str += "</b>;";
}else if(ques.quesType.indexOf("SA")>-1 && mockVar.saQName.length>0){
str += "Question Type : <b>";
str += mockVar.saQName;
str += "</b>;";
}else if(ques.quesType.indexOf("SUBJECTIVE")>-1 && mockVar.mcQName > 0){
str += "Question Type : <b>";
str += mockVar.subjQName;
str += "</b>;";
}
if(mockVar.showMarks){
str += " Marks for correct answer :<font color='green'><b> "+ ques.allottedMarks +"</b></font>";
str += "; Negative Marks :<font color='red'><b> "+ ques.negMarks +"</b></font>";
}
str += "</i><td></tr>";
str += "</table></p><hr style='color:#ccc'/>";
}
str +="<br/>";
}
$("#viewQPDiv").html(str);
showModule('QPDiv');
}
function multiLangInstru(){
$("#basInst option[value='instEnglish']").attr("selected", "selected");
if(document.getElementById("multiLangDD")!=null){
$("#multiLangDD option").each(function(){
if($(this).text().toUpperCase() == 'HINDI'){
$('#basInst').parent().show();
}
});
$("#multiLangDD").change(function (){
var select = this.value;
$("#multiLangDD option").each(function(){
if(select == this.value){
$("#instLang" + select).show();
}else{
$("#instLang" + this.value).hide();
}
});
});
}
}
/***************************************FeedBack page *********************/
function validateFeedPageURL(){
var url = document.URL;
var params = url.split("FeedBack.html?");
var orgId = $.trim(params[1]).split("@@")[0];
var mockId = $.trim(params[1]).split("@@")[1];
if(params.length>1 ){
if(mockId.indexOf("#")>-1){
mockId = mockId.substring(0,mockId.indexOf("#"));
}
if($.trim(params[1]).length>0){
validateExpiry(orgId,mockId);
var xml = readAndReturnXML(orgId+'/'+mockId+'/confDetails.xml');
basicDetails(xml);
$("#pWait").hide();
}
}else{
window.location.href="error.html";
}
}
/***************************************close page *********************/
function validateClosePageURL(){
var url = document.URL;
var params = url.split("close.html?");
var orgId = $.trim(params[1]).split("@@")[0];
var mockId = $.trim(params[1]).split("@@")[1];
if(params.length>1 ){
if(mockId.indexOf("#")>-1){
mockId = mockId.substring(0,mockId.indexOf("#"));
}
if($.trim(params[1]).length>0){
validateExpiry(orgId,mockId);
var xml = readAndReturnXML(orgId+'/'+mockId+'/confDetails.xml');
basicDetails(xml);
$("#pWait").hide();
}
}else{
window.location.href="error.html";
}
}
function activeLink(linkId){
for(var i=0;i<mockVar.activeLinkList.length;i++){
if(mockVar.activeLinkList[i]==linkId){
$("#"+mockVar.activeLinkList[i]).css("background","#2F72B7");
$("#"+mockVar.activeLinkList[i]).css("color","white");
}else{
$("#"+mockVar.activeLinkList[i]).removeAttr('style');
}
}
}
function removeActiveLinks(){
for(var i=0;i<mockVar.activeLinkList.length;i++){
$("#"+mockVar.activeLinkList[i]).removeAttr('style');
}
}
function calcTotalQues(orgId,mockId){
var quesCount = 0;
var QPxml = readAndReturnXML(orgId+'/'+mockId+'/quiz.xml');
$(QPxml).find('QUESTION').each(function(){
quesCount++;
});
$(".totalNoOfQues").html(quesCount/2);
}
function loadCalculator(){
$('#loadCalc').show();
$('#loadCalc').load('SimpleCalculator.html',function() {
$('#closeButton').click(function(){$('#loadCalc').hide();$("#showCalc").show();});
});
$("#showCalc").hide();
}
function disableTab(event){
$("textarea").keydown(function(e) {
var key_code = (window.event) ? event.keyCode : e.which;
if(key_code==9){
e.preventDefault();
}
});
}
function validateKeyBoardInputAlphaNumeric(evt, textAreaObj){
var charCode = (evt.which) ? evt.which : evt.keyCode;
if(!(evt.ctrlKey||evt.altKey) && ((charCode>=97 && charCode<=122)
||(charCode>=65 && charCode<=90) || (charCode>=48 && charCode<=57)
|| (charCode==43)|| (charCode==44) || (charCode==45) || (charCode==46) || (charCode==32)
|| (charCode==59)|| (charCode==42)|| (charCode==13)|| (charCode==33)|| (charCode==64)
|| (charCode==35)|| (charCode==36)|| (charCode==37) || (charCode==94) || (charCode==38)
|| (charCode==42)|| (charCode==40)|| (charCode==41)|| (charCode==95)|| (charCode==61)
|| (charCode==20)|| (charCode==123)|| (charCode==125)|| (charCode==91)|| (charCode==93)
|| (charCode==124)|| (charCode==92)|| (charCode==126)|| (charCode==96)
|| (charCode==58)|| (charCode==34)|| (charCode==39)|| (charCode==60)|| (charCode==62)
|| (charCode==63)|| (charCode==47)|| (charCode==106)|| (charCode==111)|| (charCode==12)
|| (charCode==8)|| (charCode==190)|| (charCode==191)|| (charCode==188)|| (charCode==222))){
return true;
}else{
return false;
}
}
function word_count() {
var number = 0;
var matches = $("#answer").val().match(/\b/g);
if(matches) {
number = matches.length/2;
}
$("#noOfWords").text( number + ' word' + (number != 1 ? 's' : '') + ' typed');
}
function caculateEllapsedTime(){
var ellapsedTime = mockVar.groups[mockVar.currentGrp].maxTime - mockVar.time;
mockVar.groups[mockVar.currentGrp].ellapsedTime = ellapsedTime;
}
function focusOnDiv(){
var divId;
if(document.getElementById('typedAnswer'))
divId = 'typedAnswer';
else if(document.getElementById('answer'))
divId = 'answer';
setTimeout(function() {
$('#'+divId).focus();
}, 0);
$('#'+divId).bind("blur", function() {
setTimeout(function() {
$('#'+divId).focus();
}, 0);
});
}// JavaScript Document