var fs = require('fs');
var sharp = require('sharp');

var dirname = process.argv.splice(2),
	path    = __dirname + '/' + dirname;

fs.readdir(path, function(err, files) { 
	if(err){
		console.log('error:'+err);
	}else{
		files.forEach(function(item){
			var prefix = item.slice(0,-4),
				suffix = item.slice(-4),
				output = path+'/'+prefix+'_ouput'+suffix;

			sharp(path+'/'+item)
			.resize(250,null)
			.toFile(output,function(err){
				if(!err){
					sharp(output)
					.extract({left:0,top:0,width:230,height:250})
					.toFile(path+'/'+prefix+'.thumb.jpg',function(err){
						if(!err){
							console.log(item+' done');
						}
					});
				}
			});
		});
	}	
});

