interface NodeWithParent {
	node: ChildNode;
	parent: HTMLElement;
	nextSibling: ChildNode | null;
}

function collectTextNodes( element: HTMLElement, collectedNodes: NodeWithParent[] = [] ): NodeWithParent[] {
	element.childNodes.forEach( ( child ) => {
		if ( child.nodeType === Node.TEXT_NODE && child.textContent && child.textContent.trim().length > 0 ) {
			collectedNodes.push( {
				node: child,
				parent: element,
				nextSibling: child.nextSibling,
			} );
		} else if ( child.nodeType === Node.ELEMENT_NODE ) {
			collectTextNodes( child as HTMLElement, collectedNodes );
		}
	} );

	return collectedNodes;
}

function addNodeWithEffect( nodeData: NodeWithParent, delay: number ): void {
	setTimeout( () => {
		const newNode = document.createElement( 'span' );
		newNode.appendChild( nodeData.node );
		newNode.style.opacity = '0';

		nodeData.parent.insertBefore( newNode, nodeData.nextSibling );

		let opacity = 0;

		setInterval( () => {
			if ( opacity < 1 ) {
				opacity += 0.1;
				newNode.style.opacity = opacity.toString();
			}
		}, 10 );
	}, delay );
}

function splitSeparateWords( nodeData: NodeWithParent, textContent: string, totalDelay: number, delayIncrement: number, separator: string ): number {
	const words = textContent.split( separator );

	words.forEach( ( word, index ) => {
		const wordNode = document.createTextNode( word );

		totalDelay += delayIncrement;

		addNodeWithEffect( {
			node: wordNode,
			parent: nodeData.parent,
			nextSibling: nodeData.nextSibling,
		}, totalDelay );

		if ( index < words.length - 1 ) {
			const spaceNode = document.createTextNode( separator );

			addNodeWithEffect( {
				node: spaceNode,
				parent: nodeData.parent,
				nextSibling: nodeData.nextSibling,
			}, totalDelay );
		}
	} );

	return totalDelay;
}

function createTypewriterEffect( rootElement: HTMLElement, nodes: NodeWithParent[] ): void {
	let totalDelay = 0;
	const delayIncrement = 200;
	const initialHeight = rootElement.offsetHeight;

	rootElement.style.minHeight = initialHeight + 'px';

	nodes.forEach( ( nodeData ) => {
		nodeData.parent.removeChild( nodeData.node );
		totalDelay += delayIncrement;

		const textContent = nodeData.node.textContent ?? '';

		if ( textContent && textContent.trim().includes( ' ' ) ) {
			totalDelay = splitSeparateWords( nodeData, textContent, totalDelay, delayIncrement, ' ' );
		} else if ( textContent && textContent.trim().includes( '.' ) ) {
			totalDelay = splitSeparateWords( nodeData, textContent, totalDelay, delayIncrement, '.' );
		} else if ( textContent && textContent.trim().includes( ':' ) ) {
			totalDelay = splitSeparateWords( nodeData, textContent, totalDelay, delayIncrement, ':' );
		} else {
			addNodeWithEffect( nodeData, totalDelay );
		}
	} );
}

function observeCodeBlocks( codeBlocks: NodeListOf<HTMLElement> ): void {
	const observer = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry ) => {
			const nodes: NodeWithParent[] = collectTextNodes( entry.target as HTMLElement );

			if ( entry.isIntersecting ) {
				createTypewriterEffect( entry.target as HTMLElement, nodes );
				observer.unobserve( entry.target );
			}
		} );
	}, {
		root: null,
		threshold: 0.1,
	} );

	codeBlocks.forEach( ( codeBlock ) => {
		observer.observe( codeBlock );
	} );
}

const codeBlocks = document.querySelectorAll( '.hljs' ) as NodeListOf<HTMLElement>;

if ( codeBlocks ) {
	observeCodeBlocks( codeBlocks );
}
