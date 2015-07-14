package com.calclavia.mavenviewer

import org.scalajs.dom

import scala.scalajs.js

/**
 * @author Calclavia
 */
object MavenViewer extends js.JSApp {

	def main() {
		val repository = new MavenRepository("http://calclavia.com/maven")
		val project = new repository.Project("dev.calclavia.electrodynamics", "electrodynamics")
		project.classifiers = Set[String]("core")
		project.generate(() => dom.document.getElementById("maven-viewer").appendChild(project.html.render))
	}
}
