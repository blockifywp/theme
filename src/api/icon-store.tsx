import { Reducer } from 'redux';
import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

interface Icons {
	[set: string]: {
		[icon: string]: object;
	};
}

interface Action {
	type: string;
	path: string;
	icons: Icons;
}

interface State {
	icons: Icons;
}

export const defaultState: State = {
	icons: {
		social: {},
		wordpress: {},
	},
};

const actions = {
	setIcons( icons: unknown ) {
		return {
			type: 'SET_ICONS',
			icons,
		};
	},
	getIcons( path: string ) {
		return {
			type: 'GET_ICONS',
			path,
		};
	},
};

const reducer: Reducer<State, any> = (
	state: State = defaultState,
	action: Action
) => {
	switch ( action.type ) {
		case 'SET_ICONS': {
			return {
				...state,
				icons: action.icons,
			};
		}
		default: {
			return state;
		}
	}
};

const selectors = {
	getIcons( state: State ) {
		const { icons } = state;
		return icons;
	},
};

const controls: { [key: string]: ( action: any ) => any } = {
	GET_ICONS( action: Action ) {
		return apiFetch( { path: action.path } );
	},
};

const resolvers = {
	*getIcons(): Generator<{ type: string; path: string }> {
		const icons = yield actions.getIcons( '/blockify/v1/icons/' );

		return actions.setIcons( icons );
	},
};

register(
	createReduxStore( 'blockify/icons', {
		reducer,
		actions,
		selectors,
		controls,
		resolvers,
	} )
);
