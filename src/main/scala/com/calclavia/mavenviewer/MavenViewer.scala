package com.calclavia.mavenviewer

import org.scalajs.dom

import scala.scalajs.js
import scalajs.js.annotation.JSExport
//import scalatags.JsDom.all._

/**
 * @author Calclavia
 */
object MavenViewer extends js.JSApp {

	def main() {
		val paragraph = dom.document.createElement("p")
		val repository = new MavenRepository("http://calclavia.com/maven", "http://calclavia.com/maven", "dev.calclavia.electrodynamics", "electrodynamics")
		paragraph.innerHTML = repository.renderBuilds()
		val id = dom.document.getElementById("maven-viewer").appendChild(paragraph)
	}
}
