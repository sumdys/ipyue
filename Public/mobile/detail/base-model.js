app.service("baseModel",["$http","configs",function(t,n){this.structureApiUrl=function(t){var e=n.api.protocol+"://"+n.api.host+n.api.basePath+t;return e},this.request=function(n,o,a,r){localStorage.getItem("token")&&(n+="?token="+localStorage.getItem("token")),"GET"==o?t.get(n,{params:a}).then(function(t){e(t,function(t,n,e){r(t,n,e)})}):"POST"==o?t.post(n,a).then(function(t){e(t,function(t,n,e){r(t,n,e)})}):"PUT"==o?t.put(n,a).then(function(t){e(t,function(t,n,e){r(t,n,e)})}):"DELETE"==o&&t.delete(n,{data:a}).then(function(t){e(t,function(t,n,e){r(t,n,e)})})},this.fileRequest=function(n,o,a,r){localStorage.getItem("token")&&(n+="?token="+localStorage.getItem("token")),t({method:o,url:n,data:a,transformRequest:function(t,n){var e=new FormData;return angular.forEach(t,function(t,n){e.append(n,t)}),e},headers:{"Content-Type":void 0}}).then(function(t){e(t,function(t,n,e){r(t,n,e)})})};var e=function(t,n){var e=t.data.error_code,o=t.data.error_msg,a=t.data.data;n(a,e,o)}}]);