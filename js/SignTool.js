class SignTool {
	constructor(canvasId) {
		this.canvas = document.getElementById(canvasId)//'main_form')
		this.initVars()
		this.initEvents()
	}

	initVars() {
		//this.canvas = $('#canvas')[0]
		//this.canvas = document.getElementById(canvasId)//'main_form')
		this.ctx = this.canvas.getContext("2d")
		this.isMouseClicked = false
		this.isMouseInCanvas = false
		this.prevX = 0
		this.currX = 0
		this.prevY = 0
		this.currY = 0
	}

	initEvents() {
	/*	$('#canvas').on("mousemove", (e) => this.onMouseMove(e))
		$('#canvas').on("mousedown", (e) => this.onMouseDown(e))
		$('#canvas').on("mouseup", () => this.onMouseUp())
		$('#canvas').on("mouseout", () => this.onMouseOut())
		$('#canvas').on("mouseenter", (e) => this.onMouseEnter(e))*/
		addEvent(this.canvas, 'mousemove', this.onMouseMove)
		addEvent(this.canvas, 'mousedown', this.onMouseDown)
		addEvent(this.canvas, 'mouseup', this.onMouseUp)
		addEvent(this.canvas, 'mouseout', this.onMouseOut)
		addEvent(this.canvas, 'mouseenter', this.onMouseEnter)
	}
	  
	onMouseDown(e) {
		this.isMouseClicked = true
		this.updateCurrentPosition(e)
	}
	  
	onMouseUp() {
		this.isMouseClicked = false
	}
	  
	onMouseEnter(e) {
		this.isMouseInCanvas = true
		this.updateCurrentPosition(e)
	}
	  
	onMouseOut() {
		this.isMouseInCanvas = false
	}

	onMouseMove(e) {
		if (this.isMouseClicked && this.isMouseInCanvas) {
			this.updateCurrentPosition(e)
			this.draw()
		}
	}
	  
	updateCurrentPosition(e) {
		this.prevX = this.currX
		this.prevY = this.currY
		this.currX = e.clientX - this.canvas.offsetLeft
		this.currY = e.clientY - this.canvas.offsetTop
	}
	  
	draw() {
		this.ctx.beginPath()
		this.ctx.moveTo(this.prevX, this.prevY)
		this.ctx.lineTo(this.currX, this.currY)
		this.ctx.strokeStyle = "black"
		this.ctx.lineWidth = 2
		this.ctx.stroke()
		this.ctx.closePath()
	}
}
/*
var canvas = new SignTool()
canvas {
	  position: absolute;
	  border: 2px solid;
	}
*/
