package com.calclavia.mavenviewer

import org.scalajs.dom

import scala.scalajs.js

/**
 * @author Calclavia
 */
object MavenViewer extends js.JSApp {
	def main() {
		val paragraph = dom.document.createElement("p")
		paragraph.innerHTML = "<strong>It works!</strong>"
		dom.document.getElementById("playground").appendChild(paragraph)
	}

	/** Computes the square of an integer.
	  * This demonstrates unit testing.
	  */
	def square(x: Int): Int = x * x
}
