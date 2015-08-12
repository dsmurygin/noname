(function(factory){if(typeof define==='function'&&define['amd']){define(typeof document!=="object"||document.readyState!=="loading"?[]:"html5-history-api",factory);}else{factory();}})(function(){var global=(typeof window==='object'?window:this)||{};if(!global.history||"emulate"in global.history)return global.history;var document=global.document;var documentElement=document.documentElement;var Object=global['Object'];var JSON=global['JSON'];var windowLocation=global.location;var windowHistory=global.history;var historyObject=windowHistory;var historyPushState=windowHistory.pushState;var historyReplaceState=windowHistory.replaceState;var isSupportHistoryAPI=!!historyPushState;var isSupportStateObjectInHistory='state'in windowHistory;var defineProperty=Object.defineProperty;var locationObject=redefineProperty({},'t')?{}:document.createElement('a');var eventNamePrefix='';var addEventListenerName=global.addEventListener?'addEventListener':(eventNamePrefix='on')&&'attachEvent';var removeEventListenerName=global.removeEventListener?'removeEventListener':'detachEvent';var dispatchEventName=global.dispatchEvent?'dispatchEvent':'fireEvent';var addEvent=global[addEventListenerName];var removeEvent=global[removeEventListenerName];var dispatch=global[dispatchEventName];var settings={"basepath":'/',"redirect":0,"type":'/',"init":0};var sessionStorageKey='__historyAPI__';var anchorElement=document.createElement('a');var lastURL=windowLocation.href;var checkUrlForPopState='';var triggerEventsInWindowAttributes=1;var isFireInitialState=false;var isUsedHistoryLocationFlag=0;var stateStorage={};var eventsList={};var lastTitle=document.title;var eventsDescriptors={"onhashchange":null,"onpopstate":null};var fastFixChrome=function(method,args){var isNeedFix=global.history!==windowHistory;if(isNeedFix){global.history=windowHistory;}method.apply(windowHistory,args);if(isNeedFix){global.history=historyObject;}};var historyDescriptors={"setup":function(basepath,type,redirect){settings["basepath"]=(''+(basepath==null?settings["basepath"]:basepath)).replace(/(?:^|\/)[^\/]*$/,'/');settings["type"]=type==null?settings["type"]:type;settings["redirect"]=redirect==null?settings["redirect"]:!!redirect;},"redirect":function(type,basepath){historyObject['setup'](basepath,type);basepath=settings["basepath"];if(global.top==global.self){var relative=parseURL(null,false,true)._relative;var path=windowLocation.pathname+windowLocation.search;if(isSupportHistoryAPI){path=path.replace(/([^\/])$/,'$1/');if(relative!=basepath&&(new RegExp("^"+basepath+"$","i")).test(path)){windowLocation.replace(relative);}}else if(path!=basepath){path=path.replace(/([^\/])\?/,'$1/?');if((new RegExp("^"+basepath,"i")).test(path)){windowLocation.replace(basepath+'#'+path.replace(new RegExp("^"+basepath,"i"),settings["type"])+windowLocation.hash);}}}},pushState:function(state,title,url){var t=document.title;if(lastTitle!=null){document.title=lastTitle;}historyPushState&&fastFixChrome(historyPushState,arguments);changeState(state,url);document.title=t;lastTitle=title;},replaceState:function(state,title,url){var t=document.title;if(lastTitle!=null){document.title=lastTitle;}delete stateStorage[windowLocation.href];historyReplaceState&&fastFixChrome(historyReplaceState,arguments);changeState(state,url,true);document.title=t;lastTitle=title;},"location":{set:function(value){if(isUsedHistoryLocationFlag===0)isUsedHistoryLocationFlag=1;global.location=value;},get:function(){if(isUsedHistoryLocationFlag===0)isUsedHistoryLocationFlag=1;return isSupportHistoryAPI?windowLocation:locationObject;}},"state":{get:function(){return stateStorage[windowLocation.href]||null;}}};var locationDescriptors={assign:function(url){if((''+url).indexOf('#')===0){changeState(null,url);}else{windowLocation.assign(url);}},reload:function(){windowLocation.reload();},replace:function(url){if((''+url).indexOf('#')===0){changeState(null,url,true);}else{windowLocation.replace(url);}},toString:function(){return this.href;},"href":{get:function(){return parseURL()._href;}},"protocol":null,"host":null,"hostname":null,"port":null,"pathname":{get:function(){return parseURL()._pathname;}},"search":{get:function(){return parseURL()._search;}},"hash":{set:function(value){changeState(null,(''+value).replace(/^(#|)/,'#'),false,lastURL);},get:function(){return parseURL()._hash;}}};function emptyFunction(){}function parseURL(href,isWindowLocation,isNotAPI){var re=/(?:(\w+\:))?(?:\/\/(?:[^@]*@)?([^\/:\?#]+)(?::([0-9]+))?)?([^\?#]*)(?:(\?[^#]+)|\?)?(?:(#.*))?/;if(href!=null&&href!==''&&!isWindowLocation){var current=parseURL(),base=document.getElementsByTagName('base')[0];if(!isNotAPI&&base&&base.getAttribute('href')){base.href=base.href;current=parseURL(base.href,null,true);}var _pathname=current._pathname,_protocol=current._protocol;href=''+href;href=/^(?:\w+\:)?\/\//.test(href)?href.indexOf("/")===0?_protocol+href:href:_protocol+"//"+current._host+(href.indexOf("/")===0?href:href.indexOf("?")===0?_pathname+href:href.indexOf("#")===0?_pathname+current._search+href:_pathname.replace(/[^\/]+$/g,'')+href);}else{href=isWindowLocation?href:windowLocation.href;if(!isSupportHistoryAPI||isNotAPI){href=href.replace(/^[^#]*/,'')||"#";href=windowLocation.protocol.replace(/:.*$|$/,':')+'//'+windowLocation.host+settings['basepath']+href.replace(new RegExp("^#[\/]?(?:"+settings["type"]+")?"),"");}}anchorElement.href=href;var result=re.exec(anchorElement.href);var host=result[2]+(result[3]?':'+result[3]:'');var pathname=result[4]||'/';var search=result[5]||'';var hash=result[6]==='#'?'':(result[6]||'');var relative=pathname+search+hash;var nohash=pathname.replace(new RegExp("^"+settings["basepath"],"i"),settings["type"])+search;return{_href:result[1]+'//'+host+relative,_protocol:result[1],_host:host,_hostname:result[2],_port:result[3]||'',_pathname:pathname,_search:search,_hash:hash,_relative:relative,_nohash:nohash,_special:nohash+hash}}function storageInitialize(){var sessionStorage;try{sessionStorage=global['sessionStorage'];sessionStorage.setItem(sessionStorageKey+'t','1');sessionStorage.removeItem(sessionStorageKey+'t');}catch(_e_){sessionStorage={getItem:function(key){var cookie=document.cookie.split(key+"=");return cookie.length>1&&cookie.pop().split(";").shift()||'null';},setItem:function(key,value){var state={};if(state[windowLocation.href]=historyObject.state){document.cookie=key+'='+JSON.stringify(state);}}}}try{stateStorage=JSON.parse(sessionStorage.getItem(sessionStorageKey))||{};}catch(_e_){stateStorage={};}addEvent(eventNamePrefix+'unload',function(){sessionStorage.setItem(sessionStorageKey,JSON.stringify(stateStorage));},false);}function redefineProperty(object,prop,descriptor,onWrapped){var testOnly=0;if(!descriptor){descriptor={set:emptyFunction};testOnly=1;}var isDefinedSetter=!descriptor.set;var isDefinedGetter=!descriptor.get;var test={configurable:true,set:function(){isDefinedSetter=1;},get:function(){isDefinedGetter=1;}};try{defineProperty(object,prop,test);object[prop]=object[prop];defineProperty(object,prop,descriptor);}catch(_e_){}if(!isDefinedSetter||!isDefinedGetter){if(object.__defineGetter__){object.__defineGetter__(prop,test.get);object.__defineSetter__(prop,test.set);object[prop]=object[prop];descriptor.get&&object.__defineGetter__(prop,descriptor.get);descriptor.set&&object.__defineSetter__(prop,descriptor.set);}if(!isDefinedSetter||!isDefinedGetter){if(testOnly){return false;}else if(object===global){try{var originalValue=object[prop];object[prop]=null;}catch(_e_){}if('execScript'in global){global['execScript']('Public '+prop,'VBScript');global['execScript']('var '+prop+';','JavaScript');}else{try{defineProperty(object,prop,{value:emptyFunction});}catch(_e_){if(prop==='onpopstate'){addEvent('popstate',descriptor=function(){removeEvent('popstate',descriptor,false);var onpopstate=object.onpopstate;object.onpopstate=null;setTimeout(function(){object.onpopstate=onpopstate;},1);},false);triggerEventsInWindowAttributes=0;}}}object[prop]=originalValue;}else{try{try{var temp=Object.create(object);defineProperty(Object.getPrototypeOf(temp)===object?temp:object,prop,descriptor);for(var key in object){if(typeof object[key]==='function'){temp[key]=object[key].bind(object);}}try{onWrapped.call(temp,temp,object);}catch(_e_){}object=temp;}catch(_e_){defineProperty(object.constructor.prototype,prop,descriptor);}}catch(_e_){return false;}}}}return object;}function prepareDescriptorsForObject(object,prop,descriptor){descriptor=descriptor||{};object=object===locationDescriptors?windowLocation:object;descriptor.set=(descriptor.set||function(value){object[prop]=value;});descriptor.get=(descriptor.get||function(){return object[prop];});return descriptor;}function addEventListener(event,listener,capture){if(event in eventsList){eventsList[event].push(listener);}else{if(arguments.length>3){addEvent(event,listener,capture,arguments[3]);}else{addEvent(event,listener,capture);}}}function removeEventListener(event,listener,capture){var list=eventsList[event];if(list){for(var i=list.length;i--;){if(list[i]===listener){list.splice(i,1);break;}}}else{removeEvent(event,listener,capture);}}function dispatchEvent(event,eventObject){var eventType=(''+(typeof event==="string"?event:event.type)).replace(/^on/,'');var list=eventsList[eventType];if(list){eventObject=typeof event==="string"?eventObject:event;if(eventObject.target==null){for(var props=['target','currentTarget','srcElement','type'];event=props.pop();){eventObject=redefineProperty(eventObject,event,{get:event==='type'?function(){return eventType;}:function(){return global;}});}}if(triggerEventsInWindowAttributes){((eventType==='popstate'?global.onpopstate:global.onhashchange)||emptyFunction).call(global,eventObject);}for(var i=0,len=list.length;i<len;i++){list[i].call(global,eventObject);}return true;}else{return dispatch(event,eventObject);}}function firePopState(){var o=document.createEvent?document.createEvent('Event'):document.createEventObject();if(o.initEvent){o.initEvent('popstate',false,false);}else{o.type='popstate';}o.state=historyObject.state;dispatchEvent(o);}function fireInitialState(){if(isFireInitialState){isFireInitialState=false;firePopState();}}function changeState(state,url,replace,lastURLValue){if(!isSupportHistoryAPI){if(isUsedHistoryLocationFlag===0)isUsedHistoryLocationFlag=2;var urlObject=parseURL(url,isUsedHistoryLocationFlag===2&&(''+url).indexOf("#")!==-1);if(urlObject._relative!==parseURL()._relative){lastURL=lastURLValue;if(replace){windowLocation.replace("#"+urlObject._special);}else{windowLocation.hash=urlObject._special;}}}else{lastURL=windowLocation.href;}if(!isSupportStateObjectInHistory&&state){stateStorage[windowLocation.href]=state;}isFireInitialState=false;}function onHashChange(event){var fireNow=lastURL;lastURL=windowLocation.href;if(fireNow){if(checkUrlForPopState!==windowLocation.href){firePopState();}event=event||global.event;var oldURLObject=parseURL(fireNow,true);var newURLObject=parseURL();if(!event.oldURL){event.oldURL=oldURLObject._href;event.newURL=newURLObject._href;}if(oldURLObject._hash!==newURLObject._hash){dispatchEvent(event);}}}function onLoad(noScroll){setTimeout(function(){addEvent('popstate',function(e){checkUrlForPopState=windowLocation.href;if(!isSupportStateObjectInHistory){e=redefineProperty(e,'state',{get:function(){return historyObject.state;}});}dispatchEvent(e);},false);},0);if(!isSupportHistoryAPI&&noScroll!==true&&"location"in historyObject){scrollToAnchorId(locationObject.hash);fireInitialState();}}function anchorTarget(target){while(target){if(target.nodeName==='A')return target;target=target.parentNode;}}function onAnchorClick(e){var event=e||global.event;var target=anchorTarget(event.target||event.srcElement);var defaultPrevented="defaultPrevented"in event?event['defaultPrevented']:event.returnValue===false;if(target&&target.nodeName==="A"&&!defaultPrevented){var current=parseURL();var expect=parseURL(target.getAttribute("href",2));var isEqualBaseURL=current._href.split('#').shift()===expect._href.split('#').shift();if(isEqualBaseURL&&expect._hash){if(current._hash!==expect._hash){locationObject.hash=expect._hash;}scrollToAnchorId(expect._hash);if(event.preventDefault){event.preventDefault();}else{event.returnValue=false;}}}}function scrollToAnchorId(hash){var target=document.getElementById(hash=(hash||'').replace(/^#/,''));if(target&&target.id===hash&&target.nodeName==="A"){var rect=target.getBoundingClientRect();global.scrollTo((documentElement.scrollLeft||0),rect.top+(documentElement.scrollTop||0)-(documentElement.clientTop||0));}}function initialize(){var scripts=document.getElementsByTagName('script');var src=(scripts[scripts.length-1]||{}).src||'';var arg=src.indexOf('?')!==-1?src.split('?').pop():'';arg.replace(/(\w+)(?:=([^&]*))?/g,function(a,key,value){settings[key]=(value||'').replace(/^(0|false)$/,'');});addEvent(eventNamePrefix+'hashchange',onHashChange,false);var data=[locationDescriptors,locationObject,eventsDescriptors,global,historyDescriptors,historyObject];if(isSupportStateObjectInHistory){delete historyDescriptors['state'];}for(var i=0;i<data.length;i+=2){for(var prop in data[i]){if(data[i].hasOwnProperty(prop)){if(typeof data[i][prop]==='function'){data[i+1][prop]=data[i][prop];}else{var descriptor=prepareDescriptorsForObject(data[i],prop,data[i][prop]);if(!redefineProperty(data[i+1],prop,descriptor,function(n,o){if(o===historyObject){global.history=historyObject=data[i+1]=n;}})){removeEvent(eventNamePrefix+'hashchange',onHashChange,false);return false;}if(data[i+1]===global){eventsList[prop]=eventsList[prop.substr(2)]=[];}}}}}historyObject['setup']();if(settings['redirect']){historyObject['redirect']();}if(settings["init"]){isUsedHistoryLocationFlag=1;}if(!isSupportStateObjectInHistory&&JSON){storageInitialize();}if(!isSupportHistoryAPI){document[addEventListenerName](eventNamePrefix+"click",onAnchorClick,false);}if(document.readyState==='complete'){onLoad(true);}else{if(!isSupportHistoryAPI&&parseURL()._relative!==settings["basepath"]){isFireInitialState=true;}addEvent(eventNamePrefix+'load',onLoad,false);}return true;}if(!initialize()){return;}historyObject['emulate']=!isSupportHistoryAPI;global[addEventListenerName]=addEventListener;global[removeEventListenerName]=removeEventListener;global[dispatchEventName]=dispatchEvent;return historyObject;});