import { Reducer } from 'redux';
import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';

export interface Icons {
	[set: string]: {
		[icon: string]: string;
	};
}

export interface IconAction {
	type: string;
	path: string;
	icons: Icons;
}

export interface IconState {
	icons: Icons;
}

export const defaultIconState: IconState = {
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

const reducer: Reducer<IconState, any> = (
	state: IconState = defaultIconState,
	action: IconAction
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
	getIcons( state: IconState ) {
		const { icons } = state;
		return icons;
	},
};

const controls: { [key: string]: ( action: any ) => any } = {
	GET_ICONS( action: IconAction ) {
		return apiFetch( { path: action.path } );
	},
};

const resolvers = {
	* getIcons(): Generator<{ type: string; path: string }> {
		const icons = yield actions.getIcons( '/blockify/v1/icons/' );

		return actions.setIcons( icons );
	},
};

export const iconStoreName = 'blockify/icons';

register(
	createReduxStore(
		iconStoreName,
		{
			reducer,
			actions,
			selectors,
			controls,
			resolvers,
		}
	)
);
