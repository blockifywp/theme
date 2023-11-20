import Editor from 'react-simple-code-editor';
import { highlight, languages } from 'prismjs/components/prism-core';
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-javascript';
import { Label, Description } from '../components';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { Button, Modal } from '@wordpress/components';
import { ucWords, replaceAll } from '../utility';

interface ModalProps {
	code: string,
	onChange: ( value: string ) => void,
	rows?: number,
	title?: string,
	description?: string,
	language?: string,
}

const CodeEditor = ( {
	code,
	onChange,
	rows = 32,
	language = 'html',
}: ModalProps ) => {
	return <>
		<Editor
			highlight={ ( value: string ) => highlight( value, languages?.[ language ] ?? 'html' ) }
			placeholder={ __( 'Please enter ', 'blockify' ) + ucWords( language ) + __( ' code here…', 'blockify' ) }
			value={ replaceAll( code, '"', "'" ) }
			onValueChange={ ( value: string ) => {
				onChange( replaceAll( value, '"', "'" ) );
			} }
			padding={ 10 }
			style={ {
				fontSize: 12,
				fontFamily: 'ui-monospace,SFMono-Regular,SF Mono,Consolas,Liberation Mono,Menlo,monospace',
				height: rows.toString() + 'em',
				maxHeight: '100%',
				borderRadius: '2px', // Core modal border-radius.

				// Material Oceanic theme.
				color: '#c3cee3',
				background: '#263238',
			} }
		/>
	</>;
};

export const CodeEditorModal = ( props: ModalProps ) => {
	const [ modalOpen, setModalOpen ] = useState( false );

	const { title, description } = props;

	const openModal = () => setModalOpen( true );
	const closeModal = () => setModalOpen( false );

	return <div
		className={ 'blockify-code-editor-modal' }
		style={ {
			width: '100%',
			display: 'flex',
			flexWrap: 'wrap',
			flexDirection: 'column',
			alignItems: 'flex-start',
		} }
	>
		<Label>
			{ title ?? '' }
		</Label>
		<Description>
			{ description ?? '' }
		</Description>
		<Button
			isSecondary
			onClick={ () => openModal() }
		>
			{ title }
		</Button>
		{ modalOpen && (
			<Modal
				title={ title ?? '' }
				onRequestClose={ () => closeModal() }
				style={ {
					width: '80%',
				} }
			>
				<CodeEditor { ...props } />
			</Modal>
		) }
	</div>;
};
