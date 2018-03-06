default:
	cd PoetArticlePoster && zip -r ../po.et.zip *
	ln README.md readme.txt || true
	zip po.et.zip README.md readme.txt
